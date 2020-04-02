<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Staff;
use App\Models\StaffScoreHistory;
use Illuminate\Support\Facades\DB;

class ClearStaffScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'score:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup and clear staffs\' score monthly';

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
        $staffListData = Staff::where('status', 1)->get();

        $staffScoreList = array();
        foreach ($staffListData as $k => $v) {
            $rewardData = $v->reward()->select('name')->first();
            $tempList = array(
                $v['true_name'],
                $v['total_score'],
                !empty($rewardData) ? $rewardData['name'] : '无',
            );
            $staffScoreList[] = $tempList;
        }

        $StaffScoreHistoryModel = new StaffScoreHistory();
        $StaffScoreHistoryModel->month = date('Y-m');
        $StaffScoreHistoryModel->score_content = json_encode($staffScoreList);

        DB::beginTransaction();
        try {
            $insertHistoryRes = $StaffScoreHistoryModel->save();

            if (!$insertHistoryRes) {
                throw new \Exception();
            }

            $updateStaffRes = Staff::where('status', 1)->update(['total_score' => 0, 'reward_id' => 0]);
            if (!$updateStaffRes) {
                throw new \Exception();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo '<pre>';
            var_dump($e->getMessage());die;
        }
    }

}
