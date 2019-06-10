<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BannerController extends Controller
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
            ->header('轮播图')
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
            ->header('详情')
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
            ->header('修改')
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
            ->header('创建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner);
      //  $grid->id('Id');
       // $grid->category_id('Category id');
        $grid->column ('category.title',' 所属分类 ');
        $grid->banner_name('轮播名字')->sortable();
        $grid->banner_desc('轮播简介');
        $grid->banner_url('轮播图')->lightbox(['zooming' => true, 'width' => 100, 'height' => 50]);
        $grid->created_at('创建时间')->sortable();
        //$grid->updated_at('Updated at','轮播名字');
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
        $show = new Show(Banner::findOrFail($id));
        $show->category('所属分类', function ($category) {
            $category->setResource('/admin/categories');
            $category->title('分类名称');
        });
      //  $show->id('Id');00
      //  $show->category_id('Category id');
        $show->banner_name('轮播名字');
        $show->banner_desc('轮播简介');
        $show->banner_url('轮播路径');
        $show->created_at('创建时间');
       // $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Banner);
        $arr = Category::get(['id', 'title'])->toArray();
        foreach ($arr as $key=>$value){
            $optionList[$value['id']] = $value['title'];
        }
        $form->select('category_id','选择分类')->options($optionList);
        $form->text('banner_name', '轮播名字');
        $form->text('banner_desc', '轮播简介');
        $form->image('banner_url','轮播路径');

        return $form;
    }
}
