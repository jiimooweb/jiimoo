<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\Photo;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\PhotoRequest;

class PhotoController extends Controller
{
    
    public function index() 
    {
        $photos = Photo::orderBy('created_at','asc')->get();

        return response()->json(['status' => 'success', 'data' => $photos]);
    }

    public function store(PhotoRequest $request) 
    {   
        $data = request()->all();
        
        if(Photo::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
    }

    public function show()
    {
        $photo = Photo::find(request()->photo);
        $status = $photo ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $photo]);
    }

    public function update(PhotoRequest $request) 
    {
        // TODO:判断更新权限
        
        $data = request()->all();
        
        if(Photo::where('id', request()->photo)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);   
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(Photo::where('id', request()->photo)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);   
    }
}
