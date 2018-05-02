<?php

namespace App\Services;

use Exception;
use App\Models\Commons\AdminUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ClientToken extends Token
{
    public function getToken($app_key, $app_secret)
    { 
        $app = AdminUser::check($app_key, $app_secret);
        
        if(!$app)
        {
            return response()->json(['msg' => '授权失败']);
        }
        else{
            $values = [
                'scope' => $app->identity,
                'uid' => $app->id
            ];
            $token = $this->saveToCache($values);
            return $token;
        }
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
}