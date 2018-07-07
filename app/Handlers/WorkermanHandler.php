<?php

namespace Handlers;

use Workerman\Worker;
use App\Models\Foods\Order;
use Illuminate\Console\Command;

class WorkermanHandler
{

    public function __construct() {

    }

    //当客户端连上来时分配uid,并保存链接,并通知所有客户端
    public function connection($connection){
        global $worker;
        // 判断当前客户端是否已经验证,既是否设置了uid
        if(!isset($connection->uid))
        {
        // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
        $connection->uid = $data;
        /* 保存uid到connection的映射，这样可以方便的通过uid查找connection，
            * 实现针对特定uid推送数据
            */
        $worker->uidConnections[$connection->uid] = $connection;
        return;
        }
    }

    //当客户端发送消息过来时,转发给所有人
    public function message($connection,$data){
        global $worker;
        if(isset($connection->uid))
        {
            // 连接断开时删除映射
            unset($worker->uidConnections[$connection->uid]);
        }
    }

    //当客户端断开时,广播给所有客户端
    public function close($connection){
      
    }

}