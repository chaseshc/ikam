<?php

namespace App\Admin\Actions\Score;

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

        $staffScoreLogModel = new StaffScoreLog;
        $staffScoreLogModel->staff_id = $staffId;
        $staffScoreLogModel->score = $score;

        DB::beginTransaction();
        try {
            $logInsertRes = $staffScoreLogModel->save();

            if (!$logInsertRes) {
                throw new \Exception();
            }

            $staffModel = Staff::find($staffId);
            $incrementRes = $staffModel->increment('total_score', $score);

            if (!$incrementRes) {
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