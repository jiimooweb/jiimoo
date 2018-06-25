<?php

namespace App\Services;

use Exception;
use App\Models\Commons\Fan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MiniProgramToken extends Token
{

    
    public function getToken(array $data) : string
    {
        $openid = $data['openid'];
        $unionid = $data['unionid'] ?? '';
        $fans = Fan::getByOpenID($openid);
        if (!$fans)
        {
            // $uid = $this->newUser($openid);
            $uid = $this->newUser($openid, $unionid);
        }
        else {
            $uid = $fans->id;
        }
        $cachedValue = $this->prepareCachedValue($data, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }


    private function prepareCachedValue(array $data, int $uid) : array
    {
        $cachedValue = $data;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = \App\Utils\RoleScope::User;
        return $cachedValue;
    }

    private function saveToCache(array $values) : string
    {

        $token = self::generateToken();
        $expire_in = config('token.token_expire_in');
        Cache::put($token, json_encode($values), $expire_in);
        if(!Cache::get($token)){
            return response()->json(['msg' => '服务器缓存异常']);
        }
        return $token;
    }

    // 创建新用户
    private function newUser(string $openid, string $unionid = '') : int
    {
        $user = Fan::create(['openid' => $openid, 'unionid' => $unionid]);
        return $user->id;
    }
}