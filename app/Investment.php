<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\Loan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount_invested','invest_rate','invest_fee','amount_total','loan_id','user_id','status_id', 'code', 'notes',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function scopePendingInstallment($query)
    {
        $query->join('loans as l', 'l.id', '=', 'investments.loan_id')
              ->where(DB::raw('DATE_FORMAT(investments.created_at, "%Y-%m-%d %H:%i")'), '<', Carbon::now()->addDays(-1)->format('Y-m-d H:i')) 
              ->where([
                'investments.paid' => 0,
                'investments.status_id' => 3,
              ])
              ->select([
                'investments.id',
                'investments.amount_invested',
                'investments.status_id',
                'investments.loan_id',
              ]);
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function loan()
    {
        return $this->belongsTo('App\Loan');
    }

    public function installments()
    {
        return $this->morphMany('App\Installment','installmentable');
    }

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
    }

    public function calculateRates()
    {
        // NOTICE: OUTDATED FUNCTION, DO NOT USE
        if($this->loan) {

            if( ($this->invest_rate !== 0) ) {

                // Calculate variables
                $invest_fee = ($this->amount_invested * ($this->invest_rate/100)) * $this->loan->tenor->month;
                $amount_total = $this->amount_invested + $invest_fee;

                // Update loan object
                $this->invest_fee = $invest_fee;
                $this->amount_total = $amount_total;
                if($this->save()) { return true; }
            }
        }
        
        return false;
    }

    public function generateInstallments() {

        if($this->loan) {
            // Check for existing installments
            if($this->installments()->count() > 0) {
                $this->installments()->forceDelete();
            } else {

                DB::beginTransaction();

                $provision_fee   = $this->loan->amount_requested * ($this->loan->provision_rate/100);
                $interest_fee    = ($this->loan->amount_requested * ($this->loan->interest_rate/100)) * $this->loan->tenor->month;
                $invest_fee      = ($this->loan->amount_requested * ($this->loan->invest_rate/100)) * $this->loan->tenor->month;

                $amountRequested = $this->amount_invested;

                $currentDay              = intval( Carbon::now()->format('d') );
                $dueDate                 = 28;
                $acceptedDate            = 13;
                $countDate               = 30;
                $pendingInstallmentValue = 0;
                $tenor                   = $this->loan->tenor->month;
                $totalInstallment        = $amountRequested / $tenor;
                $investRate              = ( $amountRequested * ($this->loan->invest_rate/100) );
                $investRateTotal         = $investRate;
                $investFee   = 0;

                $pendingUpdates = array();
                for ($i=1; $i < $tenor + 1; $i++) {
                    $y = Carbon::now()->format('d');

                    //check total day in month
                    //$dateCount = date('t');

                    if ($y <= $acceptedDate) {
                        if ($i == 1) {
                            $investRateFee   = (($dueDate - $y) / 30) * $investRateTotal;
                        }else{
                            $investRateFee   = $investRateTotal;
                        }
                        $x = $totalInstallment + $investRateFee;
                        $investFee += $investRateFee;
                        $dueDateValue = Carbon::now()->addMonths($i-1)->addDays(-$y)->addDays(28);
                    } else if ($y > $acceptedDate && $y < $dueDate) {
                        if ($i == 1) {
                            $investRateFee   = $investRateTotal + ( (($dueDate - $y) / 30) * $investRateTotal);
                        }else{
                            $investRateFee   = $investRateTotal;
                        }
                        $x = $investRateFee + $totalInstallment;
                        $investFee += $investRateFee;
                        $dueDateValue = Carbon::now()->addMonths($i)->addDays(-$y)->addDays(28);
                    } else {
                        $investRateFee   = $investRateTotal;
                        $x               = $totalInstallment + $investRateFee;
                        $investFee      += $investRateFee;
                        $dueDateValue = Carbon::now()->addMonths($i)->addDays(-$y)->addDays(28);
                    }

                    try {
                        $installmentId = \App\Installment::create([
                            'amount'               => $x,
                            'tenor'                => $i,
                            'balance'              => $x,
                            'due_date'             => $dueDateValue,
                            'installmentable_id'   => $this->id,
                            'installmentable_type' => 'App\Investment',
                        ]);  
                        $pendingInstallment = array(
                            'id' => $installmentId->id, 
                            'amount' => $x, 
                        );
                        array_push($pendingUpdates, $pendingInstallment);
                    } catch (\Exception $e){
                        DB::rollback();
                        return true;
                    }    

                    $amountTotal    = $amountRequested + $investFee;

                    $pendingInstallmentValue = $amountTotal;
                    foreach ($pendingUpdates as $pendingUpdate) {
                        $pendingInstallmentValue -= $pendingUpdate['amount'];
                        $installmentUpdate = \App\Installment::find($pendingUpdate['id']);
                        $installmentUpdate->balance = $pendingInstallmentValue;
                        $installmentUpdate->save();
                    }

                    try {
                        $this->invest_fee      = $investFee;
                        $this->amount_total    = $amountTotal;
                        $this->save();
                    } catch (\Exception $e){
                        DB::rollback();
                        return false;
                    }

                    /*$installment = new \App\Installment;
                    $installment->amount = $this->amount_total / $this->loan->tenor->month;
                    $installment->tenor = $i;
                    $installment->balance = $this->amount_total - ($installment->amount * $i);
                    $installment->due_date = $this->created_at->addDays($i * 30);
                    $installment->installmentable_id = $this->id;
                    $installment->installmentable_type = 'App\Investment';
                    $installment->save();     */  
                }

                DB::commit();
                return true;
            }
        }
    }

    public function deleteInstallments() {
        // Check for existing installments
        if(!$this->installments()->count()) {
            return false;
        } else {
            $installments = $this->installments;
            foreach ($installments as $installment) {
                $installment->delete();
            }
            return true;
        }
    }
     protected static function boot() {
        parent::boot();

        Investment::deleting(function($investment) {
            foreach ($investment->installments()->get() as $installment) {
                $installment->forceDelete();
            }
        }); 
        Investment::deleting(function($investment) {
            foreach ($investment->transactions()->get() as $transaction) {
                $transaction->forceDelete();
            }
        });       
        Investment::deleting(function($investment) {
            if ($investment->status_id == 3) {
                $loan = Loan::find($investment->loan_id);
                if (!empty($loan)) {
                    $loan->amount_funded -= $investment->amount_invested; 
                    $loan->save();   
                }
            }
        });

        static::created(function($model) {
            $user = User::find($model->user_id);
            $user->code = 'L' . $user->id;
            $user->save();

            $model->code = 'L' . $model->user_id . '-' . $model->id;
            $model->save();
        });   
    }
}
