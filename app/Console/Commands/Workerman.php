<?php

namespace App\Console\Commands;

use Workerman\Worker;
use Workerman\Lib\Timer;
use Illuminate\Console\Command;

class Workerman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman:command {action} {-d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Workerman websocket server';

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
        global $argv;
        $arg = $this->argument('action');
        var_dump($argv);
        
        $argv[1] = $arg;
        // $argv[2] = isset($argv[3]) ? "-{$argv[3]}" : '';

        define('HEARTBEAT_TIME', 30);


        $context = array(
            // 更多ssl选项请参考手册 http://php.net/manual/zh/context.ssl.php
            'ssl' => array(
                // 请使用绝对路径
                'local_cert'                 => '/usr/local/nginx/conf/ssl/ssl.crt', // 也可以是crt文件
                'local_pk'                   => '/usr/local/nginx/conf/ssl/ssl.key',
                'verify_peer'                => false,
                // 'allow_self_signed' => true, //如果是自签名证书需要开启此选项
            )
        );
        // 这里设置的是websocket协议（端口任意，但是需要保证没被其它程序占用）
        $worker = new Worker('websocket://0.0.0.0:9501', $context);
        // 设置transport开启ssl，websocket+ssl即wss
        $worker->transport = 'ssl';

        // 启动4个进程对外提供服务
        $worker->count = 1;
        // worker进程启动后创建一个text Worker以便打开一个内部通讯端口
        $worker->onWorkerStart = function($worker)
        {
            Timer::add(1, function()use($worker){
                $time_now = time();
                foreach($worker->connections as $connection) {
                    // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                    if (empty($connection->lastMessageTime)) {
                        $connection->lastMessageTime = $time_now;
                        continue;
                    }
                    // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                    if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
                        $connection->close();
                    }
                }
            });
            
            // //开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
            $inner_text_worker = new Worker('text://0.0.0.0:8150');
            $inner_text_worker->onMessage = function($connection, $buffer)
            {
                // $data数组格式，里面有uid，表示向那个uid的页面推送数据
                $data = json_decode($buffer, true);
                $uid = $data['uid'];
                // 通过workerman，向uid的页面推送数据
                $ret = $this->sendMessageByUid($uid, $buffer);
                // 返回推送结果
                $connection->send($ret ? 'ok' : 'fail');
            };
            // ## 执行监听 ##
            $inner_text_worker->listen();
        };

        // 新增加一个属性，用来保存uid到connection的映射
        $worker->uidConnections = array();

        // $handler = \App::make('Handlers\WorkermanHandler');
        $worker->onMessage = function($connection, $data)
        {
            global $worker;

            $connection->lastMessageTime = time();
            
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
        };

        // 当有客户端连接断开时
        $worker->onClose = function($connection)
        {
            global $worker;
            if(isset($connection->uid))
            {
                // 连接断开时删除映射
                unset($worker->uidConnections[$connection->uid]);
            }
        };
        

        Worker::runAll();
    }


    // 向所有验证的用户推送数据
    function broadcast($message)
    {
        global $worker;
        foreach($worker->uidConnections as $connection)
        {
            $connection->send($message);
        }
    }

    // 针对uid推送数据
    function sendMessageByUid($uid, $message)
    {
        global $worker;
        if(isset($worker->uidConnections[$uid]))
        {
            $connection = $worker->uidConnections[$uid];
            $connection->send($message);
            return true;
        }
        return false;
    }

    protected function getArguments()
    {
        return [['action',InputArgument::REQUIRED,'start|stop|restart']];
    }

}
