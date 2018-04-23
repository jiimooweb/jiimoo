<?php

namespace App\Admin\Controllers;
use App\Admin\Controllers\Controller;
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
            return "登陆成功";
        }
        return "用户或密码错误";
    }
    public function logout(){
        \Auth::guard('admin')->logout();
        return "登出";
    }
}