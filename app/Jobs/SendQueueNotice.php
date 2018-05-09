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

    private $queue_id;
    private $notice;
    private $count = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($queue_id)
    {
        $this->queue_id = $queue_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $app = Xcx::getApp(\Session::get('xcx_id'));        
        $Queue = new Queue();
        $queue = $Queue->getFansByQueueID($this->queue_id);
        file_put_contents('test.txt',$queue->template_id);
        
        foreach($queue->fans as $fan) {
            // $Queue->addNotice($fan->openid,++$this->count);
            file_put_contents('test.txt',$queue->template_id);

            $app->template_message->send([
                'touser' => $fan->openid,
                'template_id' => $queue->template_id,
                'form_id' => 'ec2cb02bc0c051dff0b2d875db13ceb5',
                'data' => [
                    'keyword1' => '任意门工作室',
                    'keyword2' => 'A002',
                    'keyword3' => ++$this->count.'桌',
                    'keyword4' => ($this->count * 6).'分钟',
                    'keyword5' => '排队中',
                    'keyword6' => '留意我们的叫号通知',
                ],
            ]);
        }
    }
}
