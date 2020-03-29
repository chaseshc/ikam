<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function scores()
    {
        return $this->hasMany(UsersScoreLog::class);
    }
}
