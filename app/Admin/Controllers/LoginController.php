<?php

namespace App\Admin\Controllers;
use App\Admin\Controllers\Controller;
use App\Models\AdminUser;

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
        $username=request('account');
        $email=	request('account');
        $password=request('password');
        $userNameAttempt=compact('username','password');
        $userEmailAttempt=compact('email','password');
        if(\Auth::guard('admins')->attempt($userNameAttempt)||
            \Auth::guard('admins')->attempt($userEmailAttempt)){
            return "成功";
        }
        return "用户或密码错误";
    }
    public function logout(){
        $user_ip=request()->getClientIp();
        $now=now();
        $user=AdminUser::where('id',\Auth::id())->update(['last_time'=>$now,'last_ip'=>$user_ip]);
        \Auth::guard('admins')->logout();
        return "登出";
    }
}