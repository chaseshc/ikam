<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffScoreLog extends Model
{
    protected $table = 'staff_score_log';

    public $scoreTypeIncrease = 1;  //增加
    public $scoreTypeDecrease = 2;  //减少

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
