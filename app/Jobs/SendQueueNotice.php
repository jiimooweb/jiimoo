<?php

namespace App\Jobs;

use App\Models\Commons\Xcx;
use App\Models\Queues\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendQueueNotice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $xcx_id;
    private $queue_id;
    private $notice;
    private $count = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($xcx_id, $queue_id)
    {
        $this->xcx_id = $xcx_id;
        $this->queue_id = $queue_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {          
        $Queue = new Queue();
        $queue = $Queue->getFansByQueueID($this->queue_id);
        foreach($queue->fans as $fan) {
            //TODO：发送通知
            $Queue->addNotice($this->xcx_id,++$this->count);
        }
        
    }
}
