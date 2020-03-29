<?php

namespace App\Admin\Actions\Score;

use App\Models\Reward;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\StaffScoreLog;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class Update extends RowAction
{

    public $name = '设置积分';

    public function handle(Model $model, Request $request)
    {
        $staffId = $model->id;
        $score = $request->get('score');

        $staffData = Staff::find($staffId);
        $beforeScore = $staffData->total_score;

        //本次积分修改后的总积分
        $afterScore = $beforeScore + $score;

        //积分奖励列表
        $rewardListData = Reward::where('status', 1)->get()->toArray();
        $rewardList = array();
        foreach ($rewardListData as $v) {
            if ($afterScore >= $v['starting_point']) {
                $rewardList[$v['id']] = $v['starting_point'];
            }
        }

        //当前总积分同时满足多个积分奖励标准，取最大值记录
        if (!empty($rewardList)) {
            $rewardIdArray = array_keys($rewardList, max($rewardList));
            $rewardId = $rewardIdArray[0];
        } else {
            $rewardId = 0;
        }

        $staffScoreLogModel = new StaffScoreLog;
        $staffScoreLogModel->staff_id = $staffId;
        $staffScoreLogModel->score = $score;

        DB::beginTransaction();
        try {
            $logInsertRes = $staffScoreLogModel->save();

            if (!$logInsertRes) {
                throw new \Exception();
            }

            $staffData->total_score = $afterScore;
            $staffData->reward_id = $rewardId;
            $updateStaffRes = $staffData->save();

            if (!$updateStaffRes) {
                throw new \Exception();
            }

            DB::commit();
            admin_toastr('更新成功！', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            admin_toastr('产生错误：'.$e->getMessage(), 'error');
        }
        return $this->response()->refresh();
    }

    public function form()
    {
        $this->text('score', '增减的积分（增加填【正数】，减少填【负数】）')->autofocus()->rules('required');
    }

}