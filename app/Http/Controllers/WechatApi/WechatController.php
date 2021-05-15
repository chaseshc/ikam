<?php
namespace App\Http\Controllers\WechatApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Date: 5/12/21
 * Time: 3:09 PM
 */
class WechatController extends Controller
{
    public function serve(){

        Log::info('request arrived');

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();
    }

    public function user(){
        $user = session('wechat.oauth_user.default');
        dd($user);
    }

}