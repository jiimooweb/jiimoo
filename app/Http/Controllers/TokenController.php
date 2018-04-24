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
        
        return response()->json(['token' => $token]);
    }
}