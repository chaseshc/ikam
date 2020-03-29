<?php

namespace App\Admin\Controllers;

use App\Models\Reward;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class Reward1Controller extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Reward';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Reward());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('score', __('Score'));
        $grid->column('starting_point', __('Starting point'));
        $grid->column('remark', __('Remark'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('score', __('Score'));
        $show->field('starting_point', __('Starting point'));
        $show->field('remark', __('Remark'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

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

        $form->text('name', __('Name'));
        $form->number('score', __('Score'));
        $form->number('starting_point', __('Starting point'));
        $form->textarea('remark', __('Remark'));

        return $form;
    }
}
