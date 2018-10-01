<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    # Tembak isi database berdasarkan tabel  
	protected $table = 'district';
    # Disable fungsi timestamps
	public $timestamps = false;

	public function provinces()
	{
		return $this->belongsTo('App\District');
	}
}
