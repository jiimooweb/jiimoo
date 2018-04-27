<?php

namespace App\Http\Controllers;


class TokenController extends Controller
{
    public function getToken()
    {

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET');

        $this->validate(request(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        $client = new \App\Services\ClientToken();

        $token = $client->getToken(request('username'), request('password'));
        
        return response()->json(['token' => $token, 'msg' => '登录成功！']);
    }
}