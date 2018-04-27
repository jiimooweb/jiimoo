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

        if( is_string($str) && strlen($token) === 32) {
            return response()->json(['token' => $token, 'msg' => '登录成功！']);
        }
        
    }
}