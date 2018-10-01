<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $fillable = [
        'id',
        'name'
    ];

    protected $table = 'companies';

    public function scopeAffiliate($query)
    {
        return $query->where('affiliate', true);
    }

    public function getIsAffiliateAttribute($query)
    {
        return $this->affiliate;
    }

    public function getNameAttribute($value)
    {
    	return ucwords($value);
    }
}
