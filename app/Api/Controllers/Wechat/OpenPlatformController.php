<?php 

namespace App\Api\Controllers\Wechat;

use App\Services\OpenPlatform;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\OpenPlatform\Server\Guard;


class OpenPlatformController extends Controller
{

    public function event_authorize(){
        $openPlatform = OpenPlatform::getApp();
        $server = $openPlatform->server;
        // 处理授权成功事件
        
        $server->push(function ($message) {
           //保存数据
            $authorizer = OpenPlatform::initOpenPlayform();

            $openPlatform = OpenPlatform::getApp();

            $miniProgram = $openPlatform->getAuthorizer($message['authorizer_appid']);
                    
            OpenPlatform::saveMiniProgram($miniProgram);

        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) {
            OpenPlatform::initOpenPlayform();
        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) {
            \Log::info('处理授权取消事件:'.$message['AuthorizerAppid']);            
        }, Guard::EVENT_UNAUTHORIZED);

        return $server->serve();
    }

    

    public function user_authorize() 
    {
        $openPlatform = OpenPlatform::getApp();        
        $url = $openPlatform->getPreAuthorizationUrl('http://www.rdoorweb.com/wechat/authorized');
        return view('/wechat',['url' => $url]);
    }

    public function authorized() 
    {
        

        return 'success';
    }

    public function token() 
    {
        $miniProgram = Xcx::find(33);
        $openPlatform = OpenPlatform::getApp();
        $server = $openPlatform->miniProgram($miniProgram['app_id'], $miniProgram['refresh_token']);
        dd($server->tester->list());
        
    }
}