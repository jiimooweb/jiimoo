<?php

namespace App\Services;

use App\Models\AdminUser;
use Illuminate\Support\Facades\Cache;

class ClientToken extends Token
{
    public function getToken($app_key, $app_secret)
    { 
        $app = AdminUser::check($app_key, $app_secret);
        dd($app);

        // if(!$app)
        // {
        //     throw new Exception([
        //         'msg' => '授权失败',
        //         'errorCode' => 10003
        //     ]);
        // }
        // else{
        //     $values = [
        //         'scope' => $app->identity,
        //         'uid' => $app->id
        //     ];
        //     $token = $this->saveToCache($values);
        //     return $token;
        // }
    }

    private function saveToCache($values){
        $token = self::generateToken();
        $expire_in = config('token.token_expire_in');
        $result = Cache::put($token, json_encode($values), $expire_in);
        if(!$result){
            throw new Exception([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $token;
    }
}