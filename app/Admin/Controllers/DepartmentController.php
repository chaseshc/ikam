<?php

namespace App\Admin\Controllers;

use App\Models\Department;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

class DepartmentController extends Controller
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
                ->header('科室')
                ->row(function (Row $row) {
                    $row->column(6, $this->treeView()->render());

                    $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action('/admin/departments');

                   // $menuModel = config('admin.database.menu_model');
                    $menuModel = new Department();
                    $form->select('parent_id', '父级分类')->options($menuModel->departmentOptions());
                    $form->text('department_name', '科室名称')->rules('required');
                    $form->text('department_dec', '科室简介')->rules('required');
                    $form->switch('is_hot', '是否热门');
                    /*$form->icon('icon', trans('admin.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('admin.uri'));
                    $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    if ((new $menuModel())->withPermission()) {
                        $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
                    }*/
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                    });
                });
    }


    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $menuModel = new Department();
        return $menuModel::tree(function (Tree $tree) {
            $tree->disableCreate();
            $tree->branch(function ($branch) {
                $payload = "<i class='fa'></i>&nbsp;<strong>{$branch['department_name']}</strong>（{$branch['department_dec']}）";
                if($branch['is_hot'] == 1){
                    $payload .= "<span class='label label-danger'>热门</span>";
                }
                return $payload;
            });
        });
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
        $grid = new Grid(new Department);

        //$grid->id('Id');
        $grid->department_name('Department name');
        $grid->is_hot('Is hot');
        $grid->created_at('Created at');
        //$grid->updated_at('Updated at');

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
        $show = new Show(Department::findOrFail($id));

        //$show->id('Id');
        $show->department_name('Department name');
        $show->is_hot('Is hot');
        $show->created_at('Created at');
        //$show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        /*$form = new Form(new Department);

        $form->text('department_name', 'Department name');
        $form->switch('is_hot', 'Is hot');

        return $form;*/

       /* $menuModel = config('admin.database.menu_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');*/
        $menuModel  = new Department();
        $form = new Form(new $menuModel());

        $form->select('parent_id','科室分类')->options($menuModel->departmentOptions());
        $form->text('department_name', '科室分类')->rules('required');
        $form->text('department_dec', '科室简介')->rules('required');
        $form->switch('is_hot', '是否热门');
        return $form;
    }
}
