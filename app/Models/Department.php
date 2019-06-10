<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class Department extends Model
{
    //
    use AdminBuilder, ModelTree {
        ModelTree::boot as treeBoot;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'department_name', 'department_dec', 'is_hot'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function departmentOptions($parentId = 0)
    {
        $option = $this::where(['parent_id' => $parentId])->pluck('department_name','id')->toArray();

        if ($parentId == 0) {
            $option[0]='顶级分类';
            ksort($option);
        }

        return $option;
    }
}
