<?php

namespace App\Models\Queues;

use Illuminate\Database\Eloquent\Model;
use App\Models\Queues\QueueFan;
use Illuminate\Support\Facades\Cache;

class Queue extends Model
{
    protected $guarded = [];
    
    public function fans() 
    {
        return $this->hasMany(QueueFan::class, 'queue_id', 'id')->where('status', 0)->orderBy('id', 'asc');
    }

    public function getOpenid($queue_id,$fan_id) 
    {
        return  optional(QueueFan::where(['queue_id' => $queue_id, 'fan_id'=> $fan_id])->first()->fan)->openid;
    }

    public function getQueueFans() 
    {
        $queues = optional($this->orderBy('id', 'asc')->withCount('fans')->get())->load('fans');
        
        if($queues) {
            foreach($queues as &$queue) {
                foreach($queue->fans as &$fan) {
                    $fan->openid =  $this->getOpenid($fan->queue_id, $fan->fan_id);
                }
            }
        }

        return $queues;
    }

    public function getFansByQueueID($queue_id)
    {
        $queue = optional($this->where('id', $queue_id)->withCount('fans')->first())->load('fans');

        if($queue) {
            foreach($queue->fans as &$fan) {
                $fan->openid =  $this->getOpenid($fan->queue_id, $fan->fan_id);
            }
        }

        return $queue;
    }

    public function addNotice($xcx_id, $count)
    {
        $data = [
            'xcx_id' => $xcx_id,
            'title' => '测试'.$count,
            'content' => '测试'
        ];

        \App\Models\Queues\QueueNotice::create($data);
    }
}
