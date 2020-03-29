<?php
namespace App\Admin\Actions\Score;

use Encore\Admin\Actions\RowAction;

/**
 * Date: 2020-03-24
 * Time: 11:01
 */

class View extends RowAction
{
    public $name = '查看积分详情';

    /**
     * @return string
     */
    public function href()
    {
        $staffId = $this->getKey();
        return route('score.show', ['staff_id' => $staffId]);
    }
}