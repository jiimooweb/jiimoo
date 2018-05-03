<?php

namespace App\Api\Controllers;
use App\Api\Controllers\Controller;
use App\Models\Commons\AdminUser;

class LoginController extends Controller
{
    //登录
    public function index(){
        return view('admin/login/index');
    }
    public function login(){
        $this->validate(request(),[
            'username'=>'required',
            'password'=>'required',
        ]);
        $client = new \App\Services\ClientToken();
        $token = $client->getToken(request('username'), request('password'));
        if(is_string($token)){
            $user_ip=request()->getClientIp();
            $now=now();
            $user=AdminUser::where('id',\Auth::id())->update(['last_time'=>$now,'last_ip'=>$user_ip]);
            return response()->json(['token' => $token, 'msg' => '登录成功！']);
        }else {
            return response()->json(['error' => $token, 'msg' => '用户名或者密码错误！']);
        }
    }
}