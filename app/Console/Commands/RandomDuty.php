<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\StaffDutyHistory;
use Illuminate\Console\Command;

class RandomDuty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'random:duty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
        $todayDuty->save();
    }
}
