<?php

namespace App\Http\Controllers\Api;

use App\Models\Staff;
use App\Models\StaffDutyHistory;
use App\Http\Controllers\Controller;

class DutyController extends Controller
{
    public function randomDuty()
    {
        //查看昨天的值日staff_id
        $yesterdayDate = date("Y-m-d", strtotime ("-1 day"));
        $todayDutyStaff = StaffDutyHistory::where('status', 1)
                        ->where('duty_date', "{$yesterdayDate}")
                        ->orderBy('created_at', 'desc')
                        ->select('staff_id')
                        ->first();

        //随机产生今日的staff_id
        $allStaffIds = Staff::where('is_vacating', '0')
            ->where('is_display', 1)
            ->where('status', 1)
            ->where('id', '<>', $todayDutyStaff->staff_id)
            ->select('id')
            ->get()
            ->toArray();

        $allStaffIds = collect($allStaffIds)->flatten()->all();
        $allStaffIdsKey = array_rand($allStaffIds);
        $todayDutyStaffId = $allStaffIds[$allStaffIdsKey];

        //添加今日值日记录之前先查看今日是否存在，已存在则将数据无效，再添加新的
        $todayDate = date('Y-m-d');
        $todayDutyStaff = StaffDutyHistory::where('status', 1)
            ->where('duty_date', "{$todayDate}")
            ->first();

        if (!empty($todayDutyStaff)) {
            StaffDutyHistory::where('status', 1)
                ->where('duty_date', "{$todayDate}")
                ->update(['status' => 0]);
        }

        //添加今日的值日记录
        $todayDuty = new StaffDutyHistory();
        $todayDuty->staff_id = $todayDutyStaffId;
        $todayDuty->duty_date = $todayDate;
        $addTodayDutyRes = $todayDuty->save();

        if ($addTodayDutyRes) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /**
     * 获取今日值日员工
     * Date: 2020-03-28
     * Time: 11:26
     * @return false|string
     */
    public function getTodayDuty()
    {
        $todayDate = date('Y-m-d');
        $todayDutyData = StaffDutyHistory::where('status', 1)
            ->where('duty_date', "{$todayDate}")
            ->orderBy('created_at', 'desc')
            ->first();

        $todayDutyStaffData = $todayDutyData->staff()->first()->toArray();

        $dutyRes = array(
            'duty_created_at' => $todayDutyData->created_at->format('Y-m-d H:i'),
            'today_duty_staff' => $todayDutyStaffData
        );
        return json_encode($dutyRes);
    }
}
