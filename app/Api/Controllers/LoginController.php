<?php

namespace App\Api\Controllers;
use App\Api\Controllers\Controller;
use App\Models\Commons\AdminUser;
use App\Http\Requests\Admin\LoginRequest;
use App\Services\Token;
class LoginController extends Controller
{
    //登录
    public function index(){
        return view('admin/login/index');
    }
    public function login(LoginRequest $request){
        $client = new \App\Services\ClientToken();
        $token = $client->getToken(request('username'), request('password'));
        if(is_string($token)){
            $user_ip=request()->getClientIp();
            $now=now();
            $user=AdminUser::orWhere('username',request('username'))->
            orWhere('email',request('username'))->
            update(['last_time'=>$now,'last_ip'=>$user_ip]);
            $user=AdminUser::find($user);
            return response()->json(['token' => $token, 'msg' => '登录成功！','user'=>$user]);
        }else {
            return response()->json(['error' => $token, 'msg' => '用户名或者密码错误！']);
        }
    }
}