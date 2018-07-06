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
        
        $ws = new Worker("websocket://0.0.0.0:9011");

        $ws->count = 4;

        $ws->onConnect = function($connection)
        {
            echo "new connection\n";
        };

        $ws->onMessage = function($connection, $data)
        {
            echo $data."\n";
            
            $connection->send('hello1');
        };

        $ws->onClose = function($connection)
        {
            echo "Connection closed\n";
        };

        // Run worker
        Worker::runAll();
    }

    protected function getArguments()
    {
        return array(
            array('action',InputArgument::REQUIRED,'start|stop|restart'),
            );
    }

}
