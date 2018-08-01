<?php

namespace App\Api\Controllers\Booking;

// use App\Models\Foods\Cate;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
// use App\Http\Requests\Foods\CateRequest;

class CategoriesController extends Controller
{
    
    public function index() 
    {
        $json_string = '[{
            id: 4632,
            name: "理发",
            data: {},
            productsCount: 2,
            coverImage: "https://nzr2ybsda.qnssl.com/images/24978/FkGlOCq6KBSMk_MRll660rVUh8xS.jpg?imageMogr2/strip/thumbnail/500x1000%3E/quality/90!/interlace/1/format/jpeg"
          }, {
            id: 4633,
            name: "造型",
            data: {},
            productsCount: 1,
            coverImage: "https://nzr2ybsda.qnssl.com/images/24978/Fpb0KJRBvf3A_7HH2tnLaUfXxNpt.jpg?imageMogr2/strip/thumbnail/500x1000%3E/quality/90!/interlace/1/format/jpeg"
          }]';
        // $obj = json_decode($json_string);
        return response()->json(['status' => 'success', 'data' => $json_string]);
    }

    public function store(CateRequest $requset) 
    {   
        // $cate = Cate::create(request(['name']));
        // if($cate) {
        //     return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $cate]);               
        // }

        // return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        // $cate = Cate::withCount('products')->find(request()->cate);             
        // $status = $cate ? 'success' : 'error';
        // return response()->json(['status' => $status, 'data' => $cate]);
    }

    public function update(CateRequest $requset) 
    {
        // if(Cate::where('id', request()->cate)->update(request(['name']))) {
        //     return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        // }

        // return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
         // TODO:判断删除权限
        // if(Cate::where('id', request()->cate)->delete()) {
        //     return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        // }
        
        // return response()->json(['status' => 'error', 'msg' => '删除失败！']);         
    }
}
