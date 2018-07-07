<?php

namespace Handlers;

use Workerman\Worker;
use App\Models\Foods\Order;
use Illuminate\Console\Command;

class WorkermanHandler
{
    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    //当客户端连上来时分配uid,并保存链接,并通知所有客户端
    public function connection($connection){
      
    }

    //当客户端发送消息过来时,转发给所有人
    public function message($connection,$data){
        echo $data;
        $connection->send('ok');
    }

    //当客户端断开时,广播给所有客户端
    public function close($connection){
      
    }

}