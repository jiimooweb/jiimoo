<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 9:13
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Commons\CommunalUser;
class UserController extends Controller
{
        // 只出现用户
        public function userLits(){
            $users=CommunalUser::where('identity','User')->paginate(15);
            dd($users);
        }
        //用户自行修改自己信息
        public function userUpdate(){
            $this->validate(request(),[
                'password'=>'required',
            ]);
            $password=request('password');
            $update=CommunalUser::where('id',\Auth::id())->update(['password'=>$password]);
            return $update;
        }
        //添加用户
        public function register(){
            $this->validate(request(),[
                'username'=>'required|unique:communal_users,username',
                'email'=>'required|email|unique:communal_users,email',
                'password'=>'required',
            ]);
            $username=request('username');
            $email=	request('email');
            $password=bcrypt(request('password'));
            $status='Y';
            $identity='User';
            $user=CommunalUser::create(compact('username','password','email','identity','status'));
            dd($user);
        }
        public function registerIndex(){
            return view('admin/register/index');
        }
        //登录
        public function loginIndex(){
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

        //删除用户
        public function deleteUser(CommunalUser $communalUser){
            $delete=CommunalUser::destroy($communalUser->id);
            return "删除";
        }
}