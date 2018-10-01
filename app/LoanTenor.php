<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LoanTenor extends Model
{
    protected $table = 'loan_tenors';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('month','asc');
        });
    }

    public function grades()
    {
        return $this->hasMany('App\LoanGrade','loan_tenor_id','id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
