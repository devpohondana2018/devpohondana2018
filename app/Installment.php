<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 
        'tenor', 
        'balance', 
        'due_date', 
        'installmentable_id', 
        'installmentable_type', 
        'paid', 
        'code'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function installmentable()
    {
        return $this->morphTo();
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }    

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getDueDateAttribute($value)
	{
	    return \Carbon\Carbon::createFromFormat('Y-m-d', $value);
	}

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
    }
    public function loans()
    {
        return $this->belongsTo(Loan::class, 'installmentable_id')
            ->where('installmentable_type', Loan::class);
    }
    public function investments()
    {
        return $this->belongsTo(Investment::class, 'installmentable_id')
            ->where('installmentable_type', Investment::class);
    }
    
    protected static function boot() {
        parent::boot();

        static::created(function($model) {
            if ($model->installmentable_type == 'App\Loan'){
                $userId = Loan::where('id', $model->installmentable_id)->first(['user_id'])->user_id;
            }else {
                $userId = Investment::where('id', $model->installmentable_id)->first(['user_id'])->user_id;
            }
            $model->code = ( $model->installmentable_type == 'App\Loan' ? 'B' : 'L') . $userId . '-' . $model->installmentable_id . '-' . $model->id;
            $model->save();
        });   
    }
}
