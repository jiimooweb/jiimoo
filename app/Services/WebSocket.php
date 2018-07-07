<?php

namespace App\Services;

use EasyWeChat\Factory;
use App\Models\Wechat\Pay;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WebSocket extends Model
{
    public static function sendOrderMsg($uid, $order ,$port = '8150') {
         // 建立socket连接到内部推送端口
        $client = stream_socket_client("tcp://127.0.0.1:$port", $errno, $errmsg, 1);
        // 推送的数据，包含uid字段，表示是给这个uid推送
        $data = array('uid'=> $uid, 'data'=> $order);
        // 发送数据，注意8150端口是Text协议的端口，Text协议需要在数据末尾加上换行符
        fwrite($client, json_encode($data)."\n");
        // 读取推送结果
        // echo fread($client, 8192);

        fclose($client);
    }
    

}
