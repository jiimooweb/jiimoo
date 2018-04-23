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

        dd(bcrypt(request('password')));
        $client = new \App\Services\ClientToken();

        $token = $client->getToken(request('username'), request('password'));

        return response()->json(['data' => $token]);
    }
}