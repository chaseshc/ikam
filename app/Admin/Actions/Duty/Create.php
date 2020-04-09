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
        $dutyDate = $request->duty_date;

        //查看昨天的值日staff_id
        $yesterdayDate = date("Y-m-d", strtotime ("-1 day"));
        $todayDutyStaff = StaffDutyHistory::where('status', 1)
            ->where('duty_date', "{$yesterdayDate}")
            ->orderBy('created_at', 'desc')
            ->select('staff_id')
            ->first();

        $staffId = !empty($todayDutyStaff) ? $todayDutyStaff->staff_id : 0;

        //随机产生今日的staff_id
        $allStaffIds = Staff::where('is_vacating', '0')
            ->where('is_display', 1)
            ->where('status', 1)
            ->where('id', '<>', $staffId)
            ->select('id')
            ->get()
            ->toArray();

        $allStaffIds = collect($allStaffIds)->flatten()->all();
        $allStaffIdsKey = array_rand($allStaffIds);
        $todayDutyStaffId = $allStaffIds[$allStaffIdsKey];

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
        $StaffDutyHistoryModel->staff_id = $todayDutyStaffId;
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
        $this->date('duty_date', '请选择值日日期')->rules('required');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-success create"><i class="fa fa-plus-square"></i> 指派</a>
HTML;
    }
}