<?php
/**
 * Date: 2020-03-28
 * Time: 19:46
 */
namespace App\Admin\Controllers;

use App\Admin\Actions\Duty\Create;
use App\Admin\Actions\Score\Update;
use App\Admin\Actions\Score\View;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffDutyHistory;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class DutyController extends Controller
{
    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('值日记录')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $staffDutyHistoryModel = new StaffDutyHistory();
        $grid = new Grid($staffDutyHistoryModel);
        $grid->model()->where('status', 1);
        $grid->model()->orderBy('created_at', 'desc');

        $grid->staff()->true_name('姓名')->sortable();
        $grid->duty_date('值日日期')->sortable()->date('Y-m-d');
        $grid->created_at('创建时间')->sortable();

        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreateButton();

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new Create());
        });

        /*$grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $allStaffList = Staff::where('status', '1')
                ->where('is_vacating', 0)
                ->get()
                ->toArray();
            $staffIds = array();
            foreach($allStaffList as $k => $v) {
                $staffIds[$v['id']] = $v['true_name'];
            }
            $create->select('staff_id', '请选择员工')->options($staffIds);
            $create->date('duty_date', '请选择日期');
        });*/

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new StaffDutyHistory());

        $form->text('staff_id', '员工ID')->readonly();

    }
}