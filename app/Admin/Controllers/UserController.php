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
        $users=AdminUser::where('identity','User');
        return response()->json(["data"=>$users]);
    }
    public function edit(AdminUser $adminUser){
        return response()->json(["data"=>compact('adminUser')]);
    }
    //用户自行修改自己信息
    public function update(AdminUser $adminUser){
        $this->validate(request(),[
            'password'=>'required',
        ]);
        $password=request('password');
        $update=$adminUser->update(['password'=>$password]);
        return response()->json(["data"=>$update]);
    }

    //添加用户
    public function store(){
        $this->validate(request(),[
            'username'=>'required|unique:admin_users,username',
            'email'=>'required|email|unique:admin_users,email',
            'password'=>'required',
        ]);
        $random=str_random(5);
        if(AdminUser::where('xxx',$random)->get()){}
        $username=request('username');
        $email=	request('email');
        $password=bcrypt(request('password'));
        $status='Y';
        $identity='User';
        $user=AdminUser::create(compact('username','password','email','identity','status'));
        return response()->json(["data"=>$user]);
    }
    public function create(){
        return view('admin/register/index');
    }
    //删除用户
    public function delete(AdminUser $adminUser){
        $xcxs=$adminUser->xcxs;
        foreach ($xcxs as $xcx){
            $adminUser->detachXcx($xcx->id);
        }
        $delete=AdminUser::destroy($adminUser->id);
        return response()->json(["data"=>$delete]);
    }

    //给用户添加小程序
    public function addXcx(AdminUser $adminUser){
        $this->validate(request(),[
            'xcxs'=>'required|array',
        ]);
        $xcxs=request('xcxs');
        $hasXcxs=$adminUser->xcxs;
        $adds=$xcxs->diff($hasXcxs);
        foreach ($adds as $add){
            $save=$adminUser->assignXcx($add);
        }
        $detachs=$hasXcxs->diff($xcxs);
        foreach ($detachs as $detach){
            $delete=$adminUser->detachXcx($detach);
        }
        if($save&&$delete){
            return response()->json(["data"=>true]);
        }else{

            return response()->json(["data"=>false]);
        }
    }
}