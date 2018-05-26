<?php

namespace App\Api\Controllers\Wechat;

use Exception;
use App\Services\Token;
use App\Models\Commons\Fan;
use App\Models\Commons\Xcx;
use Illuminate\Http\Request;
use App\Services\MiniProgramToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class MiniProgramController extends Controller
{

    public function getToken()
    {
        $app = Xcx::getApp(session('xcx_id'));

        $user = $app->auth->session(request('code'));

        // $app = OpenPlatform::getMiniProgram(session('xcx_id'));

        // $user = $app->auth->session(request('code'));

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
        // $template = $app->template_message->list(0, 20);
        // $template = $app->template_message->getTemplates(0, 1);

        $template = $app->template_message->send([
            'touser' => 'oB_Gk5PJ0yNg_ZXtuPY1WcBLJ1AU',
            'template_id' => 'WV83mpSsgqDUaC1Ah09hN19h3LTHKe-0LCBHsNkSTCY',
            'form_id' => '1525856350900',
            'data' => [
                'keyword1' => '任意门工作室',
                'keyword2' => 'A002',
                'keyword3' => '1桌',
                'keyword4' => '5分钟',
                'keyword5' => '排队中',
                'keyword6' => '浩哥没鸡鸡',
            ],
        ]);

        dd($template);
    }
}
