<?php

namespace App\Console\Commands;

use Workerman\Worker;
use Illuminate\Console\Command;

class Workerman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman:command {action} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $arg = $this->argument('action');
        switch ($arg) {
            case 'start':
                $this->info('workerman observer started');
                $this->start();
                break;
            case 'stop':
                $this->info('stoped');
                break;
            case 'restart':
                $this->info('restarted');
                break;
        }
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
        $argv [1] = $arg;

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
        $worker = new Worker('websocket://0.0.0.0:443', $context);
        // 设置transport开启ssl，websocket+ssl即wss
        $worker->transport = 'ssl';
        $worker->onMessage = function($con, $msg) {
            $con->send('ok');
        };

        Worker::runAll();
    }

    protected function getArguments()
    {
        return array(
            array('action',InputArgument::REQUIRED,'start|stop|restart'),
            );
    }

}
