<?php

namespace App\Admin\Controllers;

use App\Models\Staff;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Admin\Actions\Score\Update;
use App\Admin\Actions\Score\View;

class StaffController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('员工列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Staff);
        $grid->id('ID')->sortable();
        $grid->true_name('姓名')->sortable();
        $grid->head_img('头像')->lightbox(['zooming' => true, 'width' => 100, 'height' => 50]);
        $grid->phone('手机号')->sortable();
        $grid->column('total_score', '总积分')->sortable();

        $isVacating = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'warning'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ];
        $grid->column('is_vacating', '是否请假')->switch($isVacating)->sortable();

        $isDisplay = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ];
        $grid->column('is_display', '是否显示')->switch($isDisplay)->sortable();

        $grid->created_at('创建时间')->sortable();
        $grid->updated_at('最近修改时间')->sortable();

        $grid->actions(function ($actions) {
            $actions->add(new Update());
            $actions->add(new View());
            $actions->disableView();
        });
        $grid->disableExport();

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
        $show = new Show(Staff::findOrFail($id));

        $show->id('Id');
        $show->open_id('Open id');
        $show->head_img('Head img');
        $show->phone('Phone');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Staff);

        $form->text('id', 'ID')->readonly();
        $form->text('true_name', '姓名')->required();
        $form->image('head_img', '头像');
        $form->mobile('phone', '电话')->required();

        $isDisplay = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];

        $form->switch('is_vacating', '是否请假')->states($isDisplay)->default('0');

        $isDisplay = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $form->switch('is_display', '是否显示')->states($isDisplay)->default('1');

        $status = [
            'on'  => ['value' => 1, 'text' => '有效', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '无效', 'color' => 'danger'],
        ];
        $form->switch('status', '员工状态')->states($status)->default('1');

        $form->tools(function (Form\Tools $tools) {
            // 去掉`查看`按钮
            $tools->disableView();
        });
        return $form;
    }

}
