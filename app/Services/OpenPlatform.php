<?php
namespace App\Services;

class OpenPlatform 
{

    public static function getApp()
    {
        $openPlatform = \EasyWeChat\Factory::openPlatform(config('wechat.open_platform.default'));  

        return $openPlatform;
    }

    public static function openPlatformPost($url,$data) 
    {   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //开启该选项时，curl_exec()执行时不会直接输出都页面，而是返回字符串格式的数据，可接收 => $data = curl_exec($ch);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function miniProgramModifyDomain($access_token, $method)
    {
        $url = "https://api.weixin.qq.com/wxa/modify_domain?access_token=" . $access_token;

        $data = [
            "action" =>  $method,
            "requestdomain" => ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
            "wsrequestdomain" => ["wss://www.rdoorweb.com","wss://www.rdoorweb.com"],
            "uploaddomain" => ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
            "downloaddomain"=> ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
        ];

        return self::openPlatformPost($url, $data);
        
    }

}
