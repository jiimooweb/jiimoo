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
            $userid=Token::getUid();
            $user_ip=request()->getClientIp();
            $now=now();
            $user=AdminUser::where('id',$userid)->update(['last_time'=>$now,'last_ip'=>$user_ip]);
            return response()->json(['token' => $token, 'msg' => '登录成功！']);
        }else {
            return response()->json(['error' => $token, 'msg' => '用户名或者密码错误！']);
        }
    }
}