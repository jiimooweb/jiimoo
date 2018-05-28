<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Cash;
use App\Api\Controllers\Controller;
use App\Http\Requests\Answers\CashRequest;
class CashController extends Controller
{
        public function show()
        {
            $xcx_id=session('xcx_id');
            $cashs=Cash::where('xcx_id',$xcx_id)->get();
            return response()->json(["status"=>"success","data"=>$cashs]);
        }

        public function store(CashRequest $request)
        {
            $save=Cash::create(['ratio'=>request('ratio')]);
            if ($save){
                return response()->json(["status"=>"success","msg"=>"保存成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"保存失败！"]);
            }
        }

        public function update(CashRequest $request)
        {
            $xcx_id=session('xcx_id');
            $update=Cash::where('xcx_id',$xcx_id)->update(['ratio'=>request('ratio')]);
            if ($update){
                return response()->json(["status"=>"success","msg"=>"修改成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"修改失败！"]);
            }
        }
}
