<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statuses';

    public function getNameAttribute($value)
    {
    	return ucwords($value);
    }

    public function getDescriptionAttribute()
    {
    	$name = strtolower($this->name);
        if($name == 'pending') {
        	return 'Pengajuan masih dalam proses persetujuan';
        } elseif($name == 'approved') {
        	return 'Pengajuan disetujui oleh admin';
        } elseif($name == 'accepted') {
            return 'Pemberian telah disetujui';
        } elseif($name == 'declined') {
            return 'Pemberian tidak disetujui oleh Anda';
        } elseif($name == 'disbursed') {
            return 'Pendanaan sudah diberikan kepada peminjam';
        } elseif($name == 'overdue') {
            return 'Pengajuan sudah melewati batas pembayaran cicilan';
        } elseif($name == 'completed') {
            return 'Pengajuan sudah selesai';
        } elseif($name == 'cancelled') {
        	return 'Pengajuan tidak jadi diajukan';
        } elseif($name == 'rejected') {
            return 'Pengajuan tidak disetujui oleh Admin';
        }
    }
}
