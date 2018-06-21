<?php

namespace App\Api\Controllers\Coupons;

use Illuminate\Http\Request;
use App\Models\Coupons\Coupon;
use App\Api\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    
    public function index() 
    {
        $coupons = Coupon::orderBy('created_at','desc')->paginate(config('common.pagesize'))->toArray();   
        return response()->json(['status' => 'success', 'data' => $coupons]);   
    }

    public function store() 
    {   
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
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

        $data = request()->all();

        if(Coupon::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
        
    }

    public function show()
    {
        $coupon = Coupon::find(request()->coupon);
        $status = $coupon ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $coupon]);   
    }

    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
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

        $data = request()->all();

        if(Coupon::where('id', request()->coupon)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(Coupon::where('id', request()->coupon)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

}
