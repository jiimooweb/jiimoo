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
        return $this->hasMany(QueueFan::class, 'queue_id', 'id')->where([
                ['status', '=', 0],
                ['created_at', '>', date('Y-m-d', time())]
            ])->orderBy('id', 'asc');
    }

    public function getOpenid(int $queue_id, int $fan_id) 
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

    public function getFansByQueueID(int $queue_id)
    {
        $queue = optional($this->where('id', $queue_id)->withCount('fans')->first())->load('fans');

        if($queue) {
            foreach($queue->fans as &$fan) {
                $fan->openid =  $this->getOpenid($fan->queue_id, $fan->fan_id);
            }
        }

        return $queue;
    }

    public function getNo(int $queue_id) : string
    {
        $flag = $this->where('id', $queue_id)->pluck('flag')[0];
        $no = QueueFan::where('queue_id', $queue_id)->where('created_at', '>', date('Y-m-d', time()))->get()->count();
        return $flag.++$no;
    }
}
