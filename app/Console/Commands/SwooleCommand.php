<?php

namespace App\Console\Commands;

use App\Models\Foods\Order;
use Illuminate\Console\Command;

class SwooleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'open swoole server';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //创建Server对象，监听 127.0.0.1:9502端口
        $serv = new \swoole_server("0.0.0.0", 9501); 

        //监听连接进入事件
        $serv->on('connect', function ($serv, $fd) {  
            echo "Client: Connect.\n";
        });

        //监听数据发送事件
        $serv->on('receive', function ($serv, $fd, $from_id, $data) {
            $serv->send($fd, "Server: ".$data);
        });

        //监听连接关闭事件
        $serv->on('close', function ($serv, $fd) {
            echo "Client: Close.\n";
        });

        //启动服务器
        $serv->start(); 
    }
}
