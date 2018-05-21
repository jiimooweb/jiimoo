<?php
namespace App\Services;

use App\Models\Commons\Xcx;
use Illuminate\Support\Facades\Cache;

class OpenPlatform 
{

    public static function getApp()
    {
        $openPlatform = \EasyWeChat\Factory::openPlatform(config('wechat.open_platform.default'));  
        return $openPlatform;
    }

    public static function openPlatformPost($url,$data = '') 
    {   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //开启该选项时，curl_exec()执行时不会直接输出都页面，而是返回字符串格式的数据，可接收 => $data = curl_exec($ch);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function initOpenPlayform() 
    {
        $authorizer = self::getApp()->handleAuthorize()['authorization_info'];
        self::setAuthorizerAccessToken($authorizer);
        return $authorizer;
    }

    public static function miniProgramModifyDomain($access_token, $method)
    {
        $url = "https://api.weixin.qq.com/wxa/modify_domain?access_token=" . $access_token;
        if($method == 'get') {
            $data = ["action" =>  $method];
        }else {
            $data = [
                "action" =>  $method,
                "requestdomain" => ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
                "wsrequestdomain" => ["wss://www.rdoorweb.com","wss://www.rdoorweb.com"],
                "uploaddomain" => ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
                "downloaddomain"=> ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
            ];
        }

        return self::openPlatformPost($url, json_encode($data));
    }

    public static function miniProgramBindTester($access_token, $wechatid)
    {
        $url = "https://api.weixin.qq.com/wxa/bind_tester?access_token=" . $access_token;
        return self::openPlatformPost($url, json_encode(['wechatid' =>$userstr]));
    }

    public static function miniProgramUnbindTester($access_token, $wechatid)
    {
        $url = "https://api.weixin.qq.com/wxa/unbind_tester?access_token=" . $access_token;
        return self::openPlatformPost($url, json_encode(['userstr' =>$userstr]));
    }

    public static function miniProgramMemberAuth($access_token)
    {
        $url = "https://api.weixin.qq.com/wxa/memberauth?access_token=" . $access_token;
        return self::openPlatformPost($url, json_encode(['action' =>'get_experiencer']));
    }
    
    public static function setAuthorizerAccessToken($authorizer)
    {    
        $appid = $authorizer['authorizer_appid'];
        Cache::put($appid.'_authorizer_access_token', $authorizer['authorizer_access_token'], 120);
    }

    public static function getAuthorizerAccessToken($appid)
    {
        return [
            'authorizer_access_token' => Cache::get($appid.'_authorizer_access_token'),
        ];
    }

    public static function saveMiniProgram($miniProgram)
    {
        $authorizer_info = $miniProgram['authorizer_info'];
        $authorizer_info['service_type_info'] = $authorizer_info['service_type_info']['id'];
        $authorizer_info['verify_type_info'] = $authorizer_info['verify_type_info']['id'];
        $authorizer_info['business_info'] = serialize($authorizer_info['business_info']);
        $authorizer_info['network'] = serialize($authorizer_info['MiniProgramInfo']['network']);
        $authorizer_info['categories'] = serialize($authorizer_info['MiniProgramInfo']['categories']);
        $authorizer_info['visit_status'] = serialize($authorizer_info['MiniProgramInfo']['visit_status']);
        $authorizer_info['app_id'] = $miniProgram['authorization_info']['authorizer_appid'];         
        $authorizer_info['refresh_token'] = $miniProgram['authorization_info']['authorizer_refresh_token'];
        $authorizer_info['func_info'] = serialize($miniProgram['authorization_info']['func_info']); 
        // return Xcx::where('xcx_id', session('xcx_id'))->update($data) ? true : false;
        return Xcx::where('id', 33)->update($authorizer_info) ? true : false;
    }
}
