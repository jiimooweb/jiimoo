<?php
namespace App\Api\Controllers;


use App\Api\Controllers\Controller;
use App\Models\Commons\Xcx;
use App\Models\Commons\AdminUser;
use App\Models\Commons\XcxHasCombo;
use App\Models\Commons\Combo;
use App\Http\Requests\Admin\XcxRequest;
use App\Services\Token;
class XcxController extends Controller{

        //用户所有小程序
        public function index()
        {
            $adminUser_id=Token::getUid();
            $adminUser=AdminUser::where('id',$adminUser_id)->first();
            $xcxs=$adminUser->xcxs;
            $xcxlist=$xcxs->sortByDesc( function ($product, $key) {
                return $product['pivot']['sort'];
            });
            return response()->json(["status"=>"success","data"=>$xcxlist]);
        }

        public function create()
        {
            return view('admin/xcx/create');
        }


        //创建小程序并指定用户关系
        public function store(XcxRequest $request)
        {
            $savedata=request(['name','app_id']);
            $savedata['xcx_flag']=str_random(5);
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


        public function checkCombo()
        {
            $xcx_id=request()->xcx;
            $xcx=Xcx::find($xcx_id);
            $combos=Combo::all();
            $hasCombo=XcxHasCombo::where('xcx_id', $xcx_id)->first();
            $hasCombo=json_decode($hasCombo->modules);
            return response()->json(["status"=>"success","data"=>compact('combos','hasCombo','xcx')]);
        }


        public function update(XcxRequest $request)
        {
            $xcx_id=session('xcx_id');
            $data=request(['name','app_id','start_time','	end_time']);
            $reCombos=request('combos');
            $reModules=request('modules');
            if($reCombos&&$reModules){
                $modules=["parent"=>$reCombos,'sub'=>$reModules];
                $data['apply_modules']=$modules;
            }
            $update=Xcx::where('id',$xcx_id)->update($data);
            if ($update){
                return response()->json(["status"=>"success","msg"=>"修改成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"修改失败！"]);
            }
        }

        public function storeCombo(){
            $xcx_id=session('xcx_id');
            $reCombos=request('combos');
            $reModules=request('modules');
            $xcx=Xcx::find($xcx_id);
            $modules=["parent"=>$reCombos,'sub'=>$reModules];
            $saveCombo=$this->updateCombo($xcx,$modules);
            $modules=json_encode($modules);
            $save=XcxHasCombo::create(compact('xcx_id','modules'));
            if(!$xcx->apply_modules){
                $xcx->apply_modules=$modules;
                $xcx->save();
            }
            if ($save){
                return response()->json(["status"=>"success","msg"=>"保存成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"保存失败！"]);
            }
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
}