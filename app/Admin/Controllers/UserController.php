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
use App\Models\Commons\Xcx;

class UserController extends Controller
{
    // 只出现用户
    public function index(){
        $pagesize=config('common.pagesize');
        $users=AdminUser::where('identity','User')->paginate(20);
        return response()->json(["status"=>"success","data"=>$users]);
    }
    public function edit(){
        $adminUser=AdminUser::where('id',\Auth::id())->first();
        return response()->json(["data"=>compact('adminUser')]);
    }
    //用户自行修改自己信息
    public function update(){
        $adminUser=AdminUser::where('id',\Auth::id())->first();
        $this->validate(request(),[
            'password'=>'required',
        ]);
        $password=request('password');
        $update=$adminUser->update(['password'=>$password]);
        if ($update){
            return response()->json(["status"=>"success","msg"=>"更新成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"更新失败！"]);
        }
    }

    //添加用户
    public function store(){
        $this->validate(request(),[
            'username'=>'required|unique:admin_users,username',
            'email'=>'required|email|unique:admin_users,email',
            'password'=>'required',
        ]);
        $random=str_random(5);
        $username=request('username');
        $email=	request('email');
        $password=bcrypt(request('password'));
        $state='1';
        $identity='User';
        $user=AdminUser::create(compact('username','password','email','identity','state'));
        if ($user){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }
    public function create(){
        return view('admin/register/index');
    }
    //删除用户
    public function delete(){
        DB::transaction(function () {
        $adminUser_id=request('user_id');
        $adminUser=AdminUser::where('id',$adminUser_id)->first();
        $xcxs=$adminUser->xcxs;
            foreach ($xcxs as $xcx){
                $adminUser->detachXcx($xcx->id);
            }
            $delete=AdminUser::destroy($adminUser->id);
            if($delete){
                return response()->json(["status"=>"success","msg"=>"删除成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"删除失败！"]);
            }
        });
    }
    //小程序选择界面
    public function checkXcx(){
        $adminiUser=AdminUser::where('id',request('user_id'))->first();
        $xcxs=Xcx::all();
        $hasXcxs=$adminiUser->xcxs;
        return response()->json(["status"=>"success","data"=>compact('xcxs','hasXcxs')]);
    }
    //给用户添加小程序
    public function addXcx(){
        $this->validate(request(),[
            'xcxs'=>'required|array',
            'user_id'=>'required'
        ]);
        $adminUser_id=request('user_id');
        $adminUser=AdminUser::where('id',$adminUser_id)->first();
        $xcxs=Xcx::findMany(request('xcxs'));
        $hasXcxs=$adminUser->xcxs;
        $adds=$xcxs->diff($hasXcxs);
        foreach ($adds as $add){
            $save=$adminUser->assignXcx($add);
        }
        $detachs=$hasXcxs->diff($xcxs);
        foreach ($detachs as $detach){
            $delete=$adminUser->detachXcx($detach);
        }
        return response()->json(["status"=>"success","msg"=>"更新成功！"]);
    }
}