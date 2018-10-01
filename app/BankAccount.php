<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    protected $fillable = [
        'bank_id','user_id','account_name','account_number'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }

    public function bank()
    {
    	return $this->belongsTo('App\Bank','bank_id');
    }
    public function getAccountNumberAttribute($value)
    {
        return decrypt($value);
    }
    public function setAccountNumberAttribute($value)
    {
        $this->attributes['account_number'] = encrypt($value);
    }
}

