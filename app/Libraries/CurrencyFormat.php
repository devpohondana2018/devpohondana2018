<?php 
namespace App\Libraries;

class CurrencyFormat
{
	public static function rupiah($value, $prefix = false)
	{
		$format = number_format($value, 2, ',', '.');

		return !$prefix ? $format : 'Rp. ' . $format;
	}
}