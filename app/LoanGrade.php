<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LoanGrade extends Model
{
	protected $fillable = [
        'rank',
        'platform_rate',
        'borrower_rate',
        'lender_rate',
        'active',
        'loan_tenor_id'
    ];

    protected $table = 'loan_grades';

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function tenor()
    {
    	return $this->belongsTo('App\LoanTenor','loan_tenor_id');
    }

}
