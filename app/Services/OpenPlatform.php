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

    public static function initOpenPlatform($auth_code, $method = 'add')
    {
        $openPlatform = self::getApp();

        $info = $openPlatform->handleAuthorize($auth_code);
        //获取小程序实例
        $miniProgram = $openPlatform->miniProgram($info['authorizer_appid'], $info['authorizer_refresh_token']);
        //设置域名
        $miniProgram->domain->modify(self::miniProgramModifyDomain($method));
        
        if($method == 'delete'){
            Xcx::where('id', session('xcx_id'))->update(['authorization_status' => -1]);   
        }else{
            //获取小程序信息
            $miniProgramInfo = $openPlatform->getAuthorizer($info['authorizer_appid']);
             //保存
            self::saveMiniProgram($miniProgramInfo);
        }
    }

    public static function miniProgramModifyDomain($method)
    {
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

        return $data;
    }

    public static function saveMiniProgram($miniProgram)
    {
        $data = [];
        $authorizer_info = $miniProgram['authorizer_info'];
        $data['head_img'] = $authorizer_info['head_img'];
        $data['nick_name'] = $authorizer_info['nick_name'];
        $data['user_name'] = $authorizer_info['user_name'];
        $data['qrcode_url'] = $authorizer_info['qrcode_url'];
        $data['principal_name'] = $authorizer_info['principal_name'];
        $data['signature'] = $authorizer_info['signature'];
        $data['qrcode_url'] = $authorizer_info['qrcode_url'];
        $data['service_type_info'] = $authorizer_info['service_type_info']['id'];
        $data['verify_type_info'] = $authorizer_info['verify_type_info']['id'];
        $data['business_info'] = serialize($authorizer_info['business_info']);
        $data['network'] = serialize($authorizer_info['MiniProgramInfo']['network']);
        $data['categories'] = serialize($authorizer_info['MiniProgramInfo']['categories']);
        $data['visit_status'] = $authorizer_info['MiniProgramInfo']['visit_status'];
        $data['app_id'] = $miniProgram['authorization_info']['authorizer_appid'];         
        $data['refresh_token'] = $miniProgram['authorization_info']['authorizer_refresh_token'];
        $data['func_info'] = serialize($miniProgram['authorization_info']['func_info']); 
        $data['authorzation_status'] = 1; 
        // return Xcx::where('xcx_id', session('xcx_id'))->update($data) ? true : false;
        return Xcx::where('id', 33)->update($data) ? true : false;
    }
}
