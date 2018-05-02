<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Swiper;
use App\Models\Displays\ProductCate;
use App\Admin\Controllers\Controller;

class SwiperController extends Controller
{
    
    public function index() 
    {
        $swipers = Swiper::where('display', 1)->orderBy('created_at','asc')->get();

        foreach($swipers as &$swiper) {
            $swiper['image'] = env('APP_URL').$swiper['image'];
        }

        return response()->json(['status' => 'success', 'data' => $swipers]);
    }

    public function store(SwiperRequest $request) 
    {   
        $data = request([
            'url', 'remake', 'display'
        ]);

        $data['image'] = '/storage/'.request()->file('image')->storePublicly(md5(time()));
        
        if(Swiper::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
    }

    public function show()
    {
        $swiper = Swiper::find('id', request()->swiper);
        $status = $swiper ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $swiper]);
    }

    public function update(SwiperRequest $request) 
    {
        // TODO:判断更新权限
        
        $data = request([
            'url', 'remake', 'display'
        ]);

        $data['image'] = '/storage/'.request()->file('image')->storePublicly(md5(time()));
        
        if(Swiper::where('id', request()->swiper)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);   
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(Swiper::where('id', request()->swiper)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);   
    }
}
