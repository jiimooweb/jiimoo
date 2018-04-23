<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ClientToken extends Token
{
    protected $app_key;
    protected $app_secret;

    function __construct($app_key, $app_secret)
    {
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
    }

    public function get()
    {
        $app = AdminUser::check($this->app_key, $this->app_secret);
        if(!$app)
        {
            throw new Exception([
                'msg' => '授权失败',
                'errorCode' => 10003
            ]);
        }
        else{
            $scope = $app->scope;
            $uid = $app->id;
            $values = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = $this->saveToCache($values);
            return $token;
        }
    }

    private function saveToCache($values){
        $token = self::generateToken();
        $expire_in = config('token.token_expire_in');
        $result = cache($token, json_encode($values), $expire_in);
        if(!$result){
            throw new Exception([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $token;
    }
}