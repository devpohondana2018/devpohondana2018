<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    # Tembak isi database berdasarkan tabel  
	protected $table = 'provinces';

	protected $fillables = [
		'name',
		'regiion_id',
	];
    # Disable fungsi timestamps
	public $timestamps = false;

	public function district()
	{
		return $this->hasMany('App\District');
	}
}
