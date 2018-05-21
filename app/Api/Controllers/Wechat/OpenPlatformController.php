<?php 

namespace App\Api\Controllers\Wechat;

use App\Services\OpenPlatform;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\OpenPlatform\Server\Guard;


class OpenPlatformController extends Controller
{


    // public function eventAuthorize() {

    //     $token = config('wechat.open_platform.default.token');
    //     $encodingAesKey = config('wechat.open_platform.default.aes_key');  
    //     $appId = config('wechat.open_platform.default.app_id');        
        
    //     $timeStamp  = empty($_GET['timestamp']) ? ""  : trim($_GET['timestamp']) ;  
    //     $nonce      = empty($_GET['nonce'])     ? ""    : trim($_GET['nonce']) ;  
    //     $msg_sign   = empty($_GET['msg_signature']) ? ""    : trim($_GET['msg_signature']) ;  

    //     //接收XML数据  
    //     $encryptMsg = file_get_contents('php://input');  
    //     $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);  
    //     $xml_tree = new DOMDocument();  
    //     $xml_tree->loadXML($encryptMsg);  
    //     $array_e = $xml_tree->getElementsByTagName('Encrypt');  
    //     $encrypt = $array_e->item(0)->nodeValue;  
    //     $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";  
    //     $from_xml = sprintf($format, $encrypt);  

    //     //利用微信官方给的方法解密，$msg就是解密后的值  
    //     $msg = '';  
    //     $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);  
        
    //     $component_verify_ticket ="";  
    //     //解密成功  
    //     if ($errCode == 0) {  
    //         $xml = new DOMDocument();  
    //         $xml->loadXML($msg);  
    //         $array_e = $xml->getElementsByTagName('ComponentVerifyTicket');  
    //         $component_verify_ticket = $array_e->item(0)->nodeValue;  
    //         Cache::put('component_verify_ticket', $component_verify_ticket, 10);
    //         return "success";  
    //     }  
    //     //解密失败  
    //     else {  
    //         return "false";  
    //     }  
        
    // }

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
        // return redirect($url);
        return view('/wechat',['url' => $url]);
    }

    public function authorized() 
    {
        $openPlatform = OpenPlatform::getApp();        
        $authorizer = $openPlatform->handleAuthorize()['authorization_info'];
        OpenPlatform::setAuthorizerCache($authorizer['authorizer_appid'], $authorizer['authorizer_access_token'] ,$authorizer['authorizer_refresh_token'],serialize($authorizer['func_info']));
        dd(OpenPlatform::getAuthorizerCache($authorizer['authorizer_appid']));
        dd($infos);
    }

    public function token() 
    {
        $openPlatform = \App\Models\Commons\Xcx::getApp(3);      
        $accessToken = $openPlatform->access_token->getToken();
        dd($accessToken);
        
    }
}