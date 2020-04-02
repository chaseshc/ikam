<?php

namespace App\Admin\Actions\Score;

use App\Models\StaffScoreHistory;
use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffScoreHistoryExport;

class Export extends Action
{
    protected $selector = '.export';

    public function handle(Request $request)
    {
        $id = $request->get('month');
        $exportFilePath = date('Y-m').'积分历史.xlsx';
        Excel::store(new StaffScoreHistoryExport($id), $exportFilePath, 'export');
        return $this->response()->success('导出成功！')->download('/exports/' . $exportFilePath);
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default export">导出积分历史</a>
HTML;
    }

    public function form()
    {
        $staffScoreHistoryList = StaffScoreHistory::orderBy('created_at', 'DESC')->get()->toArray();

        $months = array();
        if (!empty($staffScoreHistoryList)) {
            foreach ($staffScoreHistoryList as $k => $v) {
                $months[$v['id']] = $v['month'];
            }
        }

        $this->select('month', '请选择月份')->options($months);
    }

}