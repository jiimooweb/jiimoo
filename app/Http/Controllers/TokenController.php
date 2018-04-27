<?php

namespace App\Http\Controllers;


class TokenController extends Controller
{
    public function getToken()
    {

        $this->validate(request(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        $client = new \App\Services\ClientToken();

        $token = $client->getToken(request('username'), request('password'));
        
        if(is_string($token)){
            return response()->json(['token' => $token, 'msg' => '登录成功！']);    
        }else {
            return response()->json(['error' => $token, 'msg' => '用户名或者密码错误！']);    
        }
    }
}