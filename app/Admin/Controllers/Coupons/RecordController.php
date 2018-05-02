<?php

namespace App\Admin\Controllers\Coupons;

use Illuminate\Http\Request;
use App\Models\Coupons\Coupon;
use App\Models\Coupons\CouponRecord;
use App\Admin\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Coupons\RecordRequest;

class RecordController extends Controller
{
    
    public function index() 
    {
        $records = CouponRecord::orderBy('created_at','desc')->paginate(config('common.pagesize')); 
        $records->load(['fan','coupon']);  
        return response()->json(['status' => 'success', 'data' => $records]);   
    }

    public function store(RecordRequest $request) 
    {   
        $data = request()->all();        
        $time = Coupon::getTime($request->coupon_id);
        $data['start_time'] = $time['start'];
        $data['end_time'] = $time['end'];
        if(CouponRecord::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
        
    }

    public function show()
    {
        $record = CouponRecord::find(request()->record);
        $status = $record ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $record]);   
    }

    public function update(RecordRequest $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'module' => 'required',
            'use_price' => 'required',
            'price' => 'required',
            'thumb' => 'required',
            'desc' => 'required',
            'receive_num' => 'required',
            'display' => 'required|integer',
            'time_type' => 'required|integer',
            'limit' => 'required|integer',
            'total' => 'required|integer',
        ]);
        $validator->sometimes(['start_time', 'end_time'], 'required|date', function ($input) {
            return $input->time_type == 0;
        });
        $validator->sometimes(['startat', 'time_limit'], 'required|integer', function ($input) {
            return $input->time_type == 1;
        });
        if($validator->errors()->count()) {
            return response()->json(['status' => 'error', 'data' => $validator->errors()]);                       
        }

        if(CouponRecord::where('id', request()->record)->update(request()->all())) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(CouponRecord::where('id', request()->record)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

}
