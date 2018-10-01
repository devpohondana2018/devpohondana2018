<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVa extends Model
{
	protected $table = 'user_va';

	public function scopeUser($query, $userId)
	{
		$query->where('user_id', $userId);
	}
	
    public function scopeGetByUserId($query, $userId)
    {
    	$query->where('user_id', $userId);
    }
}
