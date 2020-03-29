<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDutyHistory extends Model
{
    protected $table = 'staff_duty_history';

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }
}
