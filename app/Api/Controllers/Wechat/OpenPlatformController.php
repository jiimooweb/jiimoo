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
        $url = $openPlatform->getPreAuthorizationUrl('http://www.rdoorweb.com/wechat/authorized');
        return view('/wechat',['url' => $url]);
    }

    public function authorized() 
    {
        //保存数据
        $authorizer = OpenPlatform::initOpenPlayform();

        $openPlatform = OpenPlatform::getApp();

        $miniProgram = $openPlatform->miniProgram($message['AuthorizerAppid'], $authorizer['authorizer_referer_token']);

        dd($miniProgram);
        
    }

    public function token() 
    {
        $appid = 'wxc1fb7bd6c21cb0cc';
        $authorizer = OpenPlatform::getAuthorizerAccessToken($appid);
        dd($authorizer);        
        
    }
}