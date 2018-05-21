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
            \Log::info('处理授权成功事件:'.$message['AuthorizerAppid']);
        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) {
            \Log::info('处理授权更新事件:'.$message['AuthorizerAppid']);
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
        OpenPlatform::initOpenPlayform();
    }

    public function token() 
    {
        $appid = 'wxc1fb7bd6c21cb0cc';
        $authorizer = OpenPlatform::getAuthorizerCache($appid);
        // $members = OpenPlatform::miniProgramMemberAuth($authorizer['authorizer_access_token']);
        $members = OpenPlatform::miniProgramBindTester($authorizer['authorizer_access_token'], 'Stan_ff');
        
        dd($members);
        
    }
}