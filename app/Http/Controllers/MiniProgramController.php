<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\Token;
use EasyWeChat\Factory;
use App\Models\Commons\Fan;
use Illuminate\Http\Request;
use App\Services\MiniProgramToken;
use Illuminate\Support\Facades\Cache;

class MiniProgramController extends Controller
{
    public function getToken()
    {
        $config = [
            'app_id' => 'wx136ee103dff0857e',
            'secret' => '486e6adb1a75460f1308ee442be8cfa4',
        
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        
            // 'log' => [
            //     'level' => 'debug',
            //     'file' => __DIR__.'/wechat.log',
            // ],
        ];
        
        $app = Factory::miniProgram($config);

        $user = $app->auth->session(request('code'));

        $miniToken = new MiniProgramToken();
        
        $token = $miniToken->getToken($user);
        // $user = $app->auth->get($user['openid']);

        return response()->json(['token' => $token]);
    }

    public function saveInfo() {

        $token = request()->header('token');

        $data = Cache::get($token);

        $data = json_decode($data, true);

        $userInfo = request('userInfo');
        
        if(Fan::where('id', $data['uid'])->update($userInfo)){
            return response()->json('保存成功');
        }

        return response()->json('保存失败');
        
    }

    public function verifyToken() {
        
        return response()->json(['isValid' => Token::verifyToken(request()->header('token'))]);
    }
}
