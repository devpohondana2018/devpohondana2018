<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Loan extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount_requested',
        'loan_tenor_id',
        'date_expired',
        'provision_fee',
        'interest_fee',
        'amount_total',
        'amount_borrowed',
        'status_id',
        'provision_rate',
        'interest_rate',
        'loan_grade_id',
        'description',
        'invest_rate',
        'invest_fee',
        'code'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getDateExpiredAttribute($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value);
    }

    public function getAmountTotalCalculatedAttribute($value)
    {
        return $this->amount_requested+$this->interest_fee;
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function installments()
    {
        return $this->morphMany('App\Installment','installmentable');
    }

    public function investments()
    {
        return $this->hasMany('App\Investment');
    }

    public function tenor()
    {
        return $this->belongsTo('App\LoanTenor','loan_tenor_id');
    }

    public function grade()
    {
        return $this->belongsTo('App\LoanGrade','loan_grade_id');
    }

    public function validateGrade()
    {
        $user = $this->user;
        $available_grades = LoanGrade::active()->get();
        $grade = NULL;

        $assigned_grade = $this->tenor->grades()->active()->where('rank','D')->first();

        if($user->type == 'orang') {

            foreach ($available_grades as $grade) {

                $validate = false;
                $check1 = false;
                $check2 = false;
                $check3 = false;
                $check4 = false;
                $check5 = false;
                $check6 = false;

                if($grade->rank == 'A') {
                    // 1. Completed at least one loan
                    if($user->completedLoansCount > 1) { $check1 = true; }
                    // 2. Owns a house
                    if($user->ownsHouse) { $check2 = true; }
                    // 3. Salary higher than 10.000.000
                    if($user->employment_salary > 10000000) { $check3 = true; }
                    // 4. Work more than 5 years
                    if($user->employment_duration > 60) { $check4 = true; }
                    // 5. Bi checking - collect 1
                    if(($user->bi_checking) && ($user->bi_checking == 1)) { $check5 = true; }
                    // 6. Works in affiliate company
                    if($user->worksInAffiliateCompany) { $check6 = true; }
                    // Passed all checks
                    if ( ($check1 == true) && ($check2 == true) && ($check3 == true) && ($check4 == true) && ($check5 == true) && ($check6 == true)) { $validate = true; }
                } elseif ($grade->rank == 'B') {
                    // 1. Permanent employee
                    if($user->isPermanentEmployee) { $check1 = true; }
                    // 2. Salary higher than 5.000.000
                    if($user->employment_salary > 5000000) { $check2 = true; }
                    // 3. Work more than 3 years
                    if($user->employment_duration > 36) { $check3 = true; }
                    // 4. Bi checking - collect 2
                    if(($user->bi_checking) && ($user->bi_checking <= 2)) { $check4 = true; }
                    // 5. Works in affiliate company
                    if($user->worksInAffiliateCompany) { $check5 = true; }
                    // Passed all checks
                    if ( ($check1 == true) && ($check2 == true) && ($check3 == true) && ($check4 == true) && ($check5 == true) ) { $validate = true; }
                } elseif ($grade->rank == 'C') {
                    // 1. Permanent employee
                    if($user->isPermanentEmployee) { $check1 = true; }
                    // 2. Salary at least regional minimum wage (umr)
                    if($user->employment_salary >= 3300000) { $check2 = true; }
                    // 3. Work more than 1 year
                    if($user->employment_duration > 12) { $check3 = true; }
                    // 4. Bi checking minimum collect 2
                    if(($user->bi_checking) && ($user->bi_checking <= 2)) { $check4 = true; }
                    // 5. Works in affiliate company
                    if($user->worksInAffiliateCompany) { $check5 = true; }
                    // Passed all checks
                    if ( ($check1 == true) && ($check2 == true) && ($check3 == true) && ($check4 == true) && ($check5 == true) ) { $validate = true; }
                } elseif ($grade->rank == 'D') {
                    // 1. Doesn't work in affiliate company
                    if(!$user->worksInAffiliateCompany) { $check1 = true; }
                    // Passed all checks
                    if ( ($check1 == true) ) { $validate = true; }
                }

                // echo '<br>Rank:'.$grade->rank;
                // echo '<br>Check1:'.$check1;
                // echo '<br>Check2:'.$check2;
                // echo '<br>Check3:'.$check3;
                // echo '<br>Check4:'.$check4;
                // echo '<br>Check5:'.$check5;
                // echo '<br>Check6:'.$check6;

                // If valid assign and break the loop
                if($validate == true) {
                    $assigned_grade = $grade;
                    break;
                }
            }
        } elseif($user->type == 'badan') {

            foreach ($available_grades as $grade) {

                $validate = false;
                $check1 = false;
                $check2 = false;
                $check3 = false;
                $check4 = false;
                $check5 = false;
                $check6 = false;

                if($grade->rank == 'A') {
                    // 1. Affiliate company
                    if($user->worksInAffiliateCompany) { $check1 = true; }
                    // 2. Jaminan
                    if($user->collateral_amount > (1.5*$this->amount_requested)) { $check2 = true; }
                    // 3. Current ratio
                    if((($user->current_asset/$user->current_debt) > 2)) { $check3 = true; }
                    // 4. Quick ratio
                    if((($user->current_asset - $user->current_inventory)/$user->current_debt)>1) { $check4 = true; }
                    // 5. Modal disetor = pinjaman
                    if($user->current_equity == $this->amount_requested) { $check5 = true; }
                    // Passed all checks
                    if ( ($check1 == true) && ($check2 == true) && ($check3 == true) && ($check4 == true) && ($check5 == true)) { $validate = true; }
                } elseif ($grade->rank == 'B') {
                    // 2. Jaminan
                    if($user->collateral_amount > (1.3*$this->amount_requested)) { $check2 = true; }
                    // 3. Current ratio
                    if((($user->current_asset/$user->current_debt) > 1.5)) { $check3 = true; }
                    // 4. Quick ratio
                    if((($user->current_asset - $user->current_inventory)/$user->current_debt)>0.75) { $check4 = true; }
                    // 5. Modal disetor > 75% pinjaman
                    if($user->current_equity > ($this->amount_requested*0.75)) { $check5 = true; }
                    // Passed all checks
                    if ( ($check2 == true) && ($check3 == true) && ($check4 == true) && ($check5 == true)) { $validate = true; }
                } elseif ($grade->rank == 'C') {
                    // 2. Jaminan
                    if($user->collateral_amount > (1.1*$this->amount_requested)) { $check2 = true; }
                    // 3. Current ratio
                    if((($user->current_asset/$user->current_debt) > 1.1)) { $check3 = true; }
                    // 4. Quick ratio
                    if((($user->current_asset - $user->current_inventory)/$user->current_debt)>0.5) { $check4 = true; }
                    // 5. Modal disetor = pinjaman
                    if($user->current_equity > ($this->amount_requested*0.5)) { $check5 = true; }
                    // Passed all checks
                    if ( ($check2 == true) && ($check3 == true) && ($check4 == true) && ($check5 == true)) { $validate = true; }
                } elseif ($grade->rank == 'D') {
                    $validate = true;
                }

                // echo '<br>Rank:'.$grade->rank;
                // echo '<br>Check1:'.$check1;
                // echo '<br>Check2:'.$check2;
                // echo '<br>Check3:'.$check3;
                // echo '<br>Check4:'.$check4;
                // echo '<br>Check5:'.$check5;
                // echo '<br>Check6:'.$check6;

                // If valid assign and break the loop
                if($validate == true) {
                    $assigned_grade = $grade;
                    break;
                }
            }
        }

        // Default fallback
        return $assigned_grade;
    }

    public function calculateRates()
    {
        // Calculate variables
        $provision_fee = $this->amount_requested * ($this->provision_rate/100);
        $interest_fee = ($this->amount_requested * ($this->interest_rate/100)) * $this->tenor->month;
        $invest_fee = ($this->amount_requested * ($this->invest_rate/100)) * $this->tenor->month;
        $amount_borrowed = $this->amount_requested - $provision_fee;
        $amount_total = $this->amount_requested + $interest_fee;

        // Update loan object
        $this->provision_fee = $provision_fee;
        $this->interest_fee = $interest_fee;
        $this->invest_fee = $invest_fee;
        $this->amount_total = $amount_total;
        $this->amount_borrowed = $amount_borrowed;

        return $this->save() ? true : false;
    }

    public function generateInstallments() {
        DB::beginTransaction();
        $loanId = $this->id;
        // Check for existing installments
        if($this->installments()->count() > 0) {
            $this->installments()->forceDelete();
        } 

        $provision_fee   = $this->amount_requested * ($this->provision_rate/100);
        $interest_fee    = ($this->amount_requested * ($this->interest_rate/100)) * $this->tenor->month;
        $invest_fee      = ($this->amount_requested * ($this->invest_rate/100)) * $this->tenor->month;

        $amountRequested = $this->amount_requested;

        $currentDay              = intval( Carbon::now()->format('d') );
        $dueDate                 = 28;
        $acceptedDate            = 13;
        $countDate               = intval( Carbon::now()->format('t') );
        $pendingInstallmentValue = 0;
        $tenor                   = $this->tenor->month;
        $totalInstallment        = $amountRequested / $tenor;
        $interestRate            = ( $amountRequested * ($this->interest_rate/100) );
        $interestRateTotal       = $interestRate;
        $investRate              = ( $amountRequested * ($this->invest_rate/100) );
        $investRateTotal         = $investRate;

        $interestFee = 0;
        $investFee   = 0;

        $pendingUpdates = array();
        for ($i=1; $i < ($this->tenor->month)+1; $i++) {
            //check date 
            $y = Carbon::now()->format('d');

            //check total day in month
            //$dateCount = date('t');

            if ($y <= $acceptedDate) {
                if ($i == 1) {
                    $interestRateFee = ( (($dueDate - $y) / $countDate) * $interestRateTotal);
                    $investRateFee   = (($dueDate - $y) / $countDate) * $investRateTotal;
                }else{
                    $interestRateFee = $interestRateTotal;
                    $investRateFee   = $investRateTotal;
                }
                $x = $totalInstallment + $interestRateFee;
                $interestFee += $interestRateFee; 
                $investFee += $investRateFee;
                $dueDateValue = Carbon::now()->addMonths($i-1)->addDays(-$y)->addDays(28);
            } else if ($y > $acceptedDate && $y < $dueDate) {
                if ($i == 1) {
                    $interestRateFee = $interestRateTotal + ( (($dueDate - $y) / $countDate) * $interestRateTotal);
                    $investRateFee   = $interestRateTotal + ( (($dueDate - $y) / $countDate) * $interestRateTotal);
                }else{
                    $interestRateFee = $interestRateTotal;
                    $investRateFee   = $investRateTotal;
                }
                $x = $interestRateFee + $totalInstallment;
                $interestFee += $interestRateFee; 
                $investFee += $investRateFee;
                $dueDateValue = Carbon::now()->addMonths($i)->addDays(-$y)->addDays(28);
            } else if ($y > $dueDate) {
                if ($i == 1) {
                    $interestRateFee = $interestRateTotal + (($countDate - $y) + 1) * ($interestRateTotal/$dueDate);//( (($y - $dueDate) / $countDate) * $interestRateTotal);
                    $investRateFee   = $interestRateTotal + (($countDate - $y) + 1) * ($interestRateTotal/$dueDate);//( (($y - $dueDate) / $countDate) * $interestRateTotal);
                }else{
                    $interestRateFee = $interestRateTotal;
                    $investRateFee   = $investRateTotal;
                }
                $x               = $totalInstallment + $interestRateFee;
                $interestFee    += $interestRateFee; 
                $investFee      += $investRateFee;
                $dueDateValue = Carbon::now()->addMonths($i)->addDays(-$y)->addDays(28);
            } else {
                $interestRateFee = $interestRateTotal;
                $investRateFee   = $investRateTotal;
                $x               = $totalInstallment + $interestRateFee;
                $interestFee    += $interestRateFee; 
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
                    'installmentable_type' => 'App\Loan',
                ]);  
                $pendingInstallment = array(
                    'id' => $installmentId->id, 
                    'amount' => $x, 
                );
                array_push($pendingUpdates, $pendingInstallment);
            } catch (\Exception $e){
                DB::rollback();
                return false;
            }    
        }

        // Calculate variables
        $provisionFee   = $amountRequested * ($this->provision_rate/100);
        $amountBorrowed = $amountRequested - $provision_fee;
        $amountTotal    = $amountRequested + $interestFee;

        $pendingInstallmentValue = $amountTotal;
        foreach ($pendingUpdates as $pendingUpdate) {
            $pendingInstallmentValue -= $pendingUpdate['amount'];
            $installmentUpdate = \App\Installment::find($pendingUpdate['id']);
            $installmentUpdate->balance = $pendingInstallmentValue;
            $installmentUpdate->save();
        }

        // Update loan object

        try {
            $this->provision_fee   = $provision_fee;
            $this->interest_fee    = $interestFee;
            $this->invest_fee      = $investFee;
            $this->amount_total    = $amountTotal;
            $this->amount_borrowed = $amountBorrowed;
            $this->save();
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }

        DB::commit();
        return true;
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

        Loan::deleting(function($loan) {
            foreach ($loan->installments()->get() as $installment) {
                $installment->forceDelete();
            }
        }); 
        Loan::deleting(function($loan) {
            foreach ($loan->investments()->get() as $investment) {
                $investment->forceDelete();
            }
        });       

        static::created(function($model) {
            $user = User::find($model->user_id);
            $user->code = 'B' . $user->id;
            $user->save();

            $model->code = 'B' . $model->user_id . '-' . $model->id;
            $model->save();
        });   
    }
}
