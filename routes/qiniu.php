<?php

Route::post('qiniuUpload', function() {
    $file = request()->file('file');
    $disk = \zgldh\QiniuStorage\QiniuStorage::disk('qiniu');
    $fileName = session('xcx_flag').'/'.date('Y-m-d',time()).'/'.md5($file->getClientOriginalName().time().rand()).'.'.$file->getClientOriginalExtension();
    $bool = $disk->put($fileName, file_get_contents($file->getRealPath()));
   // 判断是否上传成功
   if ($bool) {
        $path = $disk->downloadUrl($fileName);
        return response()->json(['status' => 'success', 'url' => $path]);
   }
   return response()->json(['status' => 'error']);
});


Route::post('qiniuDelete', function() {
    $url = request('url');
    $filename = basename($url);
    $disk = \zgldh\QiniuStorage\QiniuStorage::disk('qiniu');
    if($disk->delete($filename)) {
        return response()->json(['status' => 'success', 'msg' => '删除成功']);
    }
    return response()->json(['status' => 'error', 'msg' => '删除失败']);
});