<?php

namespace App\Utils;

class Wechat
{
    public static function retMsg($msg)
    {
        if($msg['errcode'] == 0) {
            return $msg;
        }
        $msg['errmsg'] = config('errcode.'.$msg['errcode']);
        return $msg;
    }
}