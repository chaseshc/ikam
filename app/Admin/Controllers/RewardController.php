<?php

namespace App\Admin\Controllers;

use App\Models\Reward;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RewardController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '积分奖励';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Reward());

        $grid->column('icon', __('图标'))->lightbox(['zooming' => true, 'width' => 30, 'height' => 30]);
        $grid->column('name', __('奖励名称'))->sortable();
        $grid->column('score', __('奖励积分价值'))->sortable();
        $grid->column('starting_point', __('奖励积分基点'))->sortable();
        $grid->column('starting_point', __('奖励积分基点'))->sortable();
        $grid->column('created_at', __('创建时间'))->sortable();
        $grid->column('updated_at', __('更新时间'))->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Reward::findOrFail($id));

        $show->field('name', __('奖励名称'));
        $show->field('score', __('奖励积分价值'));
        $show->field('starting_point', __('奖励积分基点'));
        $show->icon('图标')->image();
        $show->field('remark', __('备注'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Reward());

        $form->text('name', __('奖励名称'));
        $form->number('score', __('奖励积分价值'));
        $form->number('starting_point', __('奖励积分基点'));
        $form->image('icon', '图标');
        $form->textarea('remark', __('备注'));

        return $form;
    }
}
