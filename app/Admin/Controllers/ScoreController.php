<?php
/**
 * Date: 2020-03-24
 * Time: 10:18
 */
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffScoreLog;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;


class ScoreController extends Controller
{
    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($staffId, Content $content)
    {
        $staffModel = Staff::findOrFail($staffId);

        $header = '<em>' . $staffModel->true_name . '</em> 的积分详情';

        return $content
            ->header($header)
            ->body($this->grid($staffId));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($staffId)
    {
        $staffScoreLogModel = new StaffScoreLog();
        $grid = new Grid($staffScoreLogModel);

        $grid->model()->where('staff_id', $staffId);
        $grid->model()->orderBy('created_at', 'desc');

        $grid->created_at('创建时间')->sortable();

        $grid->column('score_type', '积分类型')->display(function ($scoreType) {
            if ($scoreType == 1) {
                return '<span class="label label-success"><span class="glyphicon glyphicon-plus"></span>增加</span>';
            } else {
                return '<span class="label label-warning"><span class="glyphicon glyphicon-minus"></span>减少</span>';
            }
        })->sortable();
        $grid->column('score', '积分')->sortable();

        $grid->enableHotKeys(['left']);

        $grid->disableExport();
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->disableCreateButton();

        return $grid;
    }
}