<?php

namespace App\Api\Controllers\Wechat;

use Exception;
use App\Services\Token;
use App\Models\Commons\Fan;
use App\Models\Commons\Xcx;
use Illuminate\Http\Request;
use App\Services\OpenPlatform;
use App\Services\MiniProgramToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class MiniProgramController extends Controller
{

    public function getToken()
    {
        // $app = Xcx::getApp(session('xcx_id'));

        // $user = $app->auth->session(request('code'));

        $app = OpenPlatform::getMiniProgram(session('xcx_id'));

        $user = $app->auth->session(request('code'));

        $miniToken = new MiniProgramToken();
        
        $token = $miniToken->getToken($user);

        return response()->json(['token' => $token]);
    }

    public function saveInfo() 
    {

        $token = request()->header('token');

        $data = Cache::get($token);

        $data = json_decode($data, true);

        $userInfo = request('userInfo');
        $userInfo['xcx_id'] = session('xcx_id');
        $userInfo['nickname'] = $userInfo['nickName'];
        $userInfo['status'] = 1;
        
        unset($userInfo['nickName']);

        if(Fan::where('id', $data['uid'])->update($userInfo)){
            return response()->json('保存成功');
        }

        return response()->json('保存失败');
        
    }

    public function verifyToken() 
    {
        return response()->json(['isValid' => Token::verifyToken(request()->header('token'))]);
    }

    public function getMiniCode() 
    {
        $app = Xcx::getApp(3);
        $response = $app->app_code->get('path/to/page');
        $filename = $response->save('wechat/miniprogram/','minicode.png');
        return $filename;
    }

    public function getQrCode() 
    {
        $app = Xcx::getApp(3);
        $response = $app->app_code->getQrCode('/path/to/page');
        $filename = $response->save('wechat/miniprogram/','appcode.png');
        return $filename;
    }

    public function test() {
        $app = Xcx::getApp(3);
        // return $app->data_cube->summaryTrend('2018-05-30','2018-05-30');
        return $app->data_cube->dailyVisitTrend('2018-05-30','2018-05-30');
    }
}
