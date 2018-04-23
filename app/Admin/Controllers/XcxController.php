<?php
namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Commons\Xcx;
use App\Models\Commons\AdminUser;
use App\Models\Commons\XcxHasCombo;
use App\Models\Commons\Combo;
class XcxController extends Controller{
        //用户所有小程序
        public function xcxList(AdminUser $adminUser){
            if($adminUser->identity=='Admin'){
                $xcxlist=Xcx::all();
            }else{
                $xcxs=$adminUser->xcxs;
                $xcxlist=Xcx::find($xcxs);
            }
            return $xcxlist;
        }

        public function createIndex(){
            return view('admin/xcx/create');
        }
        //创建小程序并指定用户关系
        public function create(){
            $this->validate(request(),[
                'userid'=>'required',
                'name'=>'required|unique:xcxs,name',
                'appid'=>'required|unique:xcxs,appid',
            ]);
            $save=Xcx::create(request(['name','appid']));
            if ($save){
                $user=AdminUser::find(request('userid'));
                $assgin=$user->assignXcx($save);
            }
            return $save;
        }
        //选择所需套餐
        public function checkCombo(Xcx $xcx){
            $combos=Combo::all();
            $hasCombo=XcxHasCombo::find($xcx->id);
            if ($hasCombo){
                $hasCombo=json_decode($hasCombo->modules);
                return view('admin/xcx/checkCombo',compact('combos','hasCombo','xcx'));
            }
            return view('admin/xcx/checkCombo',compact('combos','xcx'));
        }
        //保存小程序套餐关系
        public function storeCombo(Xcx $xcx){

            $reCombos=request('combos');
            $reModules=request('modules');
            $modules=["parent"=>$reCombos,'sub'=>$reModules];
            $modules=json_encode($modules);
            $xcx_id=$xcx->id;
            $save=XcxHasCombo::create(compact('xcx_id','modules'));
            return $save;
        }
}