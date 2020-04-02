<?php

namespace App\Exports;

use App\Models\StaffScoreHistory;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffScoreHistoryExport implements FromArray, WithHeadings
{
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function headings(): array
    {
        return [
            '员工名称',
            '总积分',
            '奖励名称',
        ];
    }

    public function array(): array
    {
        $historyData = StaffScoreHistory::find($this->id);
        $historyContentJson = $historyData->score_content;
        $historyContentArray = json_decode($historyContentJson, true);

        return $historyContentArray;
    }
}
