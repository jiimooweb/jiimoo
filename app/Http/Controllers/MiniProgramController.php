<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyWeChat\Factory;

class MiniProgramController extends Controller
{
    public function index()
    {
        $config = [
            'app_id' => 'wx136ee103dff0857e',
            'secret' => '486e6adb1a75460f1308ee442be8cfa4',
        
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        
            // 'log' => [
            //     'level' => 'debug',
            //     'file' => __DIR__.'/wechat.log',
            // ],
        ];
        
        $app = Factory::miniProgram($config);

        $userSummary = $app->data_cube;

        var_dump($userSummary);
        dd($app);
    }
}
