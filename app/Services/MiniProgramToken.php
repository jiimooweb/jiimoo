<?php

namespace App\Services;

use Exception;
use App\Models\Commons\Fan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MiniProgramToken extends Token
{

    
    public function getToken($data)
    {
        $openid = $data['openid'];
        $fans = Fan::getByOpenID($openid);
        if (!$fans)
        {
            $uid = $this->newUser($openid);
        }
        else {
            $uid = $fans->id;
        }
        $cachedValue = $this->prepareCachedValue($data, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }


    private function prepareCachedValue($data, $uid)
    {
        $cachedValue = $data;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = \App\Utils\RoleScope::User;
        return $cachedValue;
    }

    private function saveToCache($values){

        $token = self::generateToken();
        $expire_in = config('token.token_expire_in');
        Cache::put($token, json_encode($values), $expire_in);
        if(!Cache::get($token)){
            return response()->json(['msg' => '服务器缓存异常']);
        }
        return $token;
    }

    // 创建新用户
    private function newUser($openid)
    {
        $user = Fan::create(['openid' => $openid]);
        return $user->id;
    }
}