<?php

namespace App\Api\Controllers\Foods;

use App\Services\Map;
use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Foods\Address;
use App\Models\Commons\BasicInfo;
use App\Api\Controllers\Controller;
use App\Http\Requests\Foods\AddressRequest;

class AddressController extends Controller
{
    
    public function index() 
    {
        
        $address = Address::where('fan_id', Token::getUid())->get();
        
        return response()->json(['status' => 'success', 'data' => $address]);
    }

    public function store(AddressRequest $requset) 
    {   
        $data = request()->all();
        $data['fan_id'] = Token::getUid();
        $address = Address::create($data);
        if($address) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $address]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        $address = Address::find(request()->address);             
        $status = $address ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $address]);
    }

    public function update(AddressRequest $requset) 
    {
        if(Address::where('id', request()->address)->update(request()->all())) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
        // TODO:判断删除权限
        if(Address::where('id', request()->address)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        }
        
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);         
    }

    public function getuser()
    {
        $address = Address::where('fan_id', Token::getUid())->first();
        if(isset($address)) {
            return response()->json(['status' => 'success']);               
        }

        return response()->json(['status' => 'error']);               
    }

    public function calc_distance()
    {
        $info = BasicInfo::first();
        $lon1 = $info->lon; //经度
        $lat1 = $info->lat; //纬度
        $lon2 = request('lon');
        $lat2 = request('lat');
        $distance = Map::GetDistance($lon1, $lat1, $lon2, $lat2);
        return response()->json(['status' => 'success', 'distance' => $distance]);               
    }
}
