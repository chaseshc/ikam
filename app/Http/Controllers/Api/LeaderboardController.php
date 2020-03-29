<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Carbon\Carbon;

class LeaderboardController extends Controller
{
    public function getLeaderboard()
    {
        $leaderboardData = Staff::where('status', 1)
            ->where('is_display', 1)
            ->orderBy('total_score', 'desc')->get();

        foreach ($leaderboardData as $k => $v) {
            //取每个用户当天的获得的积分总和
            $leaderboardData[$k]['today_score'] = $v->scores()->whereBetween('created_at', [Carbon::today()->startOfDay(),Carbon::today()->endOfDay()])->sum('score');
            //格式化用户头像地址
            $leaderboardData[$k]['head_img'] = "background-image: url('/uploads/" . $v['head_img'] . "')";
        }

        return json_encode($leaderboardData);
    }
}
