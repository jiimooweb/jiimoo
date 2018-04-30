<?php

namespace App\Admin\Controllers;
use App\Admin\Controllers\Controller;
use App\Models\Commons\AdminUser;

class LoginController extends Controller
{
    //登录
    public function index(){
        return view('admin/login/index');
    }
    public function login(){
        $this->validate(request(),[
            'account'=>'required',
            'password'=>'required',
        ]);
        $client = new \App\Services\ClientToken();
        $token = $client->getToken(request('username'), request('password'));
        return response()->json(["data"=>$token]);
    }

}