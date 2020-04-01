<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/getLeaderboard', '\App\Http\Controllers\Api\LeaderboardController@getLeaderboard');    //积分榜
Route::get('/randomTodayDuty', '\App\Http\Controllers\Api\DutyController@randomDuty');              //随机生成今日值日生
Route::get('/getTodayDuty', '\App\Http\Controllers\Api\DutyController@getTodayDuty');               //获取今日值日生