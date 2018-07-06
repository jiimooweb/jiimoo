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
        $server = new \swoole_websocket_server("0.0.0.0", 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP | SWOOLE_SSL);

        $server->set([
                'work_num'=>1,
                'ssl_cert_file'=> '/usr/local/nginx/conf/ssl/1527670808768.pem',
                'ssl_key_file' => '/usr/local/nginx/conf/ssl/1527670808768.key'
            ]);

        $server->on('open', function (\swoole_websocket_server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $server->on('message', function (\swoole_websocket_server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });

        $server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });

        $server->start();
    }
}
