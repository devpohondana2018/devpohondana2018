<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
	protected $fillable = ['token'];
    
    public function getRouteKeyName()
    {
    	return 'token';
    }
    
    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }

}
