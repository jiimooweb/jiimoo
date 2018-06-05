<?php
namespace App\Api\Controllers;


use App\Api\Controllers\Controller;
use App\Models\Commons\Module;
use App\Models\Commons\Xcx;
use App\Models\Commons\AdminUser;
use App\Models\Commons\XcxHasCombo;
use App\Models\Commons\Combo;
use App\Http\Requests\Admin\XcxRequest;
use App\Services\Token;
use Validator;
class XcxController extends Controller{

        //用户所有小程序
        public function index()
        {
            $adminUser_id=Token::getUid();
            $admin = AdminUser::where('id',$adminUser_id)->with(['xcxs' => function ($query) {
                $page=request('page');
                $pagesize=config('common.pagesize');
//                $offset=($page - 1) * $pagesize;->offset($offset)->limit($pagesize)
                $query->orderBy('sort', 'desc')->paginate($pagesize);
            }])->first();
            $xcxlist=$admin->xcxs;
            return response()->json(["status"=>"success","data"=>$xcxlist]);
        }

        public function create()
        {
            return view('admin/xcx/create');
        }


        //创建小程序并指定用户关系
        public function store(XcxRequest $request)
        {
            $valid=Validator::make(request()->all(), [
                'nick_name'=>'unique:xcxs,nick_name',
            ]);
            if($valid->errors()->count()){
                return response()->json(["status"=>"error","data"=>$valid->errors()]);
            }
            $savedata=request(['nick_name','start_time','end_time']);
            $savedata['xcx_flag']=str_random(8);
            $save=Xcx::create($savedata);
            if ($save){
                $adminUser=AdminUser::find(1);
                $adminUser->assignXcx($save);
                if(request('user_id')){
                    $user=AdminUser::find(request('user_id'));
                    $assgin=$user->assignXcx($save);
                    return response()->json(["status"=>"success","msg"=>"保存成功！"]);
                }
                return response()->json(["status"=>"success","msg"=>"保存成功！"]);
            }
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }

        public function hasCombo()
        {
            $xcx_flag=request()->xcx_flag;
            $xcx=Xcx::where('xcx_flag',$xcx_flag)->first();
            $hasCombo=XcxHasCombo::where('xcx_id', $xcx->id)->first();
            $hasCombo=json_decode($hasCombo->modules,true);
            return response()->json(["status"=>"success","data"=>$hasCombo]);
        }

        public function choiceCombo()
        {
            $xcx_flag=request()->xcx_flag;
            $xcx=Xcx::where('xcx_flag',$xcx_flag)->first();
//            $xcx_id=request('xcx_id');
//            $xcx=Xcx::find($xcx_id);
            $combos=Combo::get();
            foreach ($combos as $combo){
                $combo->module;
            }
            $hasCombo=XcxHasCombo::where('xcx_id', $xcx->id)->first();
            if($hasCombo){
                $hasCombo=json_decode($hasCombo->modules,true);
            }else{
                $hasCombo=[];
            }
            return response()->json(["status"=>"success","data"=>compact('combos','hasCombo','xcx','modules')]);
        }

        public function storeCombo(XcxRequest $request){
            $xcx_flag=request()->xcx_flag;
            $data=request(['nick_name','star_time','end_time','apply_modules']);
            $update=Xcx::where('xcx_flag',$xcx_flag)->update($data);
            $reCombos=request('combos');
            $reModules=request('modules');
            if ($reCombos&&$reModules){
                $xcx=Xcx::where('xcx_flag',$xcx_flag)->first();
                $xcx_id=$xcx->id;
//                $reCombos=Combo::findMany($reCombos);
//                $reModules=Module::findMany($reModules);
                $modules=["parent"=>$reCombos,'sub'=>$reModules];
                $modules=json_encode($modules);
                $save=XcxHasCombo::updateOrCreate(compact('xcx_id'),compact('modules'));
                if(!$xcx->apply_modules){
                    $xcx->apply_modules=$modules;
                    $xcx->save();
                }
            }
                return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }

        public function destroy()
        {
            $xcx_id=request()->xcx;
            $xcx=Xcx::find($xcx_id);
            $users=$xcx->user;
            foreach ($users as $user){
                $xcx->detachUser($user->id);
            }
            $deletehas=XcxHasCombo::where('xcx_id',$xcx->id)->delete();
            $delete=Xcx::destroy($xcx->id);
            if($delete){
                return response()->json(["status"=>"success","msg"=>"删除成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"删除失败！"]);
            }
        }




    //小程序选择界面
    public function choiceUser()
    {
        $xcx_flag=request()->xcx_flag;
        $xcx=Xcx::where('xcx_flag',$xcx_flag)->with(['user' => function ($query) {
            $query->where('user_id','>',1);
        }])->first();
        $hasUsers=$xcx->user;
        $users=AdminUser::where('id','>',1)->get();
        return response()->json(["status"=>"success","data"=>compact('hasUsers','users')]);
    }

    //给用户添加小程序
    public function updateUser()
    {
        $valid=Validator::make(request()->all(), [
            'user_ids'=>'required|array',
            'xcx_flag'=>'required'
        ]);
        if($valid->errors()->count()){
            return response()->json(["status"=>"error","data"=>$valid->errors()]);
        }
        $xcx_flag=request()->xcx_flag;
        $xcx=Xcx::where('xcx_flag',$xcx_flag)->first();
        $hasUsers=$xcx->user;
        $users=AdminUser::findMany(request('user_ids'));
        $adds=$users->diff($hasUsers);
        foreach ($adds as $add){
            $xcx->assignUser($add);
        }
        $detachs=$hasUsers->diff($users);
        foreach ($detachs as $detach){
            $xcx->detachUser($detach);
        }
        return response()->json(["status"=>"success","msg"=>"更新成功！"]);
    }
}