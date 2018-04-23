<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 9:13
 */

namespace App\Admin\Controllers;


use App\Admin\Controllers\Controller;
use App\Models\Commons\AdminUser;
class UserController extends Controller
{
    // 只出现用户
    public function index(){
        $users=AdminUser::where('identity','User')->paginate(15);
        dd($users);
    }
    public function edit(AdminUser $adminUser){
        return view('',compact('adminUser'));
    }
    //用户自行修改自己信息
    public function update(){
        $this->validate(request(),[
            'password'=>'required',
        ]);
        $password=request('password');
        $update=AdminUser::where('id',\Auth::id())->update(['password'=>$password]);
        return $update;
    }
    //添加用户
    public function store(){
        $this->validate(request(),[
            'username'=>'required|unique:admin_users,username',
            'email'=>'required|email|unique:admin_users,email',
            'password'=>'required',
        ]);
        $username=request('username');
        $email=	request('email');
        $password=bcrypt(request('password'));
        $status='Y';
        $identity='User';
        $user=AdminUser::create(compact('username','password','email','identity','status'));
        dd($user);
    }
    public function create(){
        return view('admin/register/index');
    }
    //删除用户
    public function delete(AdminUser $adminUser){
        $delete=AdminUser::destroy($adminUser->id);
        return "删除";
    }
}