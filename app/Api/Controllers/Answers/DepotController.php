<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Depot;
use App\Api\Controllers\Controller;
use Validator;
use App\Http\Requests\Answers\DepotRequest;
use App\Models\Answers\Activitie;
class DepotController extends Controller
{
        public function index()
        {
            $pagesize=config('common.pagesize');
            $depot=Depot::paginate($pagesize);
            return response()->json(["status"=>"success","data"=>$depot]);
        }

        public function show()
        {
            $depot=Depot::all();
            return response()->json(["status"=>"success","data"=>$depot]);
        }

        public function store(DepotRequest $request)
        {
            $save=Depot::create(request(['name','desc']));
            if ($save){
                return response()->json(["status"=>"success","msg"=>"保存成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"保存失败！"]);
            }
        }


        public function update(DepotRequest $request)
        {
            $depot_id=request()->depot;
            $depot=Depot::find($depot_id);
            $update = $depot->update(request(['name', 'desc']));
            if ($update){
                return response()->json(["status"=>"success","msg"=>"修改成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"修改失败！"]);
            }
        }

        public function destroy()
        {
            $depot_id=request()->depot;
            $depot=Depot::find($depot_id);
            if (count($depot->question)){
                return response()->json(["status"=>"error","msg"=>"删除失败！"]);
            }else{
                $hasActivities=$depot->activitie;
                foreach ($hasActivities as $hasActivity){
                    $depot->detachActivitie($hasActivity);
                }
                $delete=$depot->delete();
                if($delete){
                    return response()->json(["status"=>"success","msg"=>"删除成功！"]);
                }else{
                    return response()->json(["status"=>"error","msg"=>"删除失败！"]);
                }
            }
        }
}
