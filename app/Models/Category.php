<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * 获取分类
     * @return mixed
     */
    public function getCategories()
    {
        return $this::where('status', 1)->get()->toArray();
    }
}
