<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ArticleController extends Controller
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
            ->header('文章管理')
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
            ->header('修改文章')
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
            ->header('新建文章')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article);

        $grid->id('Id')->sortable();
        $grid->column('category.title','所属分类')->sortable();
        $grid->title('标题')->sortable();
        $grid->cover('封面')->lightbox(['zooming' => true, 'width' => 100, 'height' => 50]);
        $grid->status('状态')->switch()->sortable();
        $grid->created_at('创建时间')->sortable();

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
        $article = Article::findOrFail($id);
        $show = new Show($article);

        $show->id('Id');
        $show->category_id('所属分类')->as(function($id) {
            return Category::find($id)->title;
        });

        $show->title('标题');
        $show->cover('封面')->image();
        $show->content('内容')->unescape();
        $show->status('状态')->unescape()->as(function ($status) {
            if ($status == 1) {
                return '<span class="label label-success">显示</span>';
            } else {
                return '<span class="label label-danger">隐藏</span>';
            }
        });
        $show->created_at('创建时间');
        $show->updated_at('更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Article);

        $categoryModel = new Category();
        $categories = $categoryModel->getCategories();

        $categoriesList = [];
        foreach ($categories as $k => $v) {
            $categoriesList[$v['id']] = $v['title'];
        }

        $form->select('category_id', '分类')->options($categoriesList);
        $form->text('title', '标题');
        $form->image('cover', '封面');
        $form->editor('content', '内容');
        $form->switch('status', '状态')->default(1);

        return $form;
    }
}
