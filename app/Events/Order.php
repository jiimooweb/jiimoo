<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Order implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //可添加任意成员变量
    public $id;
    
    //事件构造函数
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    //自定义广播的消息名
    public function broadcastAs()
    {
        return 'anyName';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('orderStatus');
    }
}
