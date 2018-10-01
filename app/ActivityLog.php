<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
	protected $table = 'activity_log';
    //

    public function admin_users()
    {
        return $this->belongsTo(AdminUser::class, 'causer_id');
    }
}
