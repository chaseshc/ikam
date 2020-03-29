<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';

    public function scores()
    {
        return $this->hasMany('App\Models\StaffScoreLog');
    }
}
