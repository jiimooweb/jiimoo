<?php
namespace App\Services;

class OpenPlatform 
{

    public static function getApp()
    {
        $openPlatform = \EasyWeChat\Factory::openPlatform(config('wechat.open_platform.default'));  

        return $openPlatform;
    }

}
