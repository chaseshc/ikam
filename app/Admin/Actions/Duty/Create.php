<?php

namespace App\Admin\Actions\Duty;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\StaffDutyHistory;
use Illuminate\Support\Facades\DB;

class Create extends Action
{
    protected $selector = '.create';

    public function handle(Request $request)
    {
        $staffId = $request->staff_id;
        $dutyDate = $request->duty_date;

        $todayDutyStaff = StaffDutyHistory::where('status', 1)
            ->where('duty_date', "{$dutyDate}")
            ->first();

        if (!empty($todayDutyStaff)) {
            StaffDutyHistory::where('status', 1)
                ->where('duty_date', "{$dutyDate}")
                ->update(['status' => 0]);
        }

        //添加今日的值日记录
        $StaffDutyHistoryModel = new StaffDutyHistory();
        $StaffDutyHistoryModel->staff_id = $staffId;
        $StaffDutyHistoryModel->duty_date = $dutyDate;

        DB::beginTransaction();
        try {
            $addDutyRes = $StaffDutyHistoryModel->save();
            if (!$addDutyRes) {
                throw new \Exception();
            }

            DB::commit();
            admin_toastr('更新成功！', 'success');
        }  catch (\Exception $e) {
            DB::rollBack();
            admin_toastr('产生错误：'.$e->getMessage(), 'error');
        }
        return $this->response()->refresh();


        if ($addDutyRes) {
            echo 'true';
        } else {
            echo 'false';
        }

        return $this->response()->success('Success message...')->refresh();
    }

    public function form()
    {
        $allStaffList = Staff::where('status', '1')
            ->where('is_vacating', 0)
            ->get()
            ->toArray();
        $staffIds = array();
        foreach($allStaffList as $k => $v) {
            $staffIds[$v['id']] = $v['true_name'];
        }
        $this->select('staff_id', '请选择员工')->options($staffIds);
        $this->date('duty_date', '请选择值日日期');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-success create"><i class="fa fa-plus-square"></i> 指派</a>
HTML;
    }
}