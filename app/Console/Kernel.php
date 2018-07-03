<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            $now = new Carbon(Carbon::now()->format('Y-m-d H:i'));
            $updatedAt = Carbon::now();
            $voteStart = Redis::hgetall('vote_start');
            $voteDue = Redis::hgetall('vote_due');
            $applyStart = Redis::hgetall('vote_apply_start');
            $applyDue = Redis::hgetall('vote_apply_due');
            //ID数组，V：投票；A：报名；D：结束；S：开始。
            $resultVD = [];
            $resultVS = [];
            $resultAD = [];
            $resultAS = [];
            foreach ($voteDue as $id => $value) {
                $date = new Carbon($value);
                if ($now->gte($date)) {
                    array_push($resultVD, $id);
                }
            }
            foreach ($voteStart as $id => $value) {
                if (array_key_exists($id, $resultVD)) {
                    Redis::hdel('vote_start', $id);
                } else {
                    $date = new Carbon($value);
                    if ($now->gte($date)) {
                        array_push($resultVS, $id);
                    }

                }
            }
            foreach ($applyDue as $id => $value) {
                $date = new Carbon($value);
                if ($now->gte($date)) {
                    array_push($resultAD, $id);
                }
            }
            foreach ($applyStart as $id => $value) {
                if (array_key_exists($id, $resultAD)) {
                    Redis::hdel('vote_apply_start', $id);
                } else {
                    $date = new Carbon($value);
                    if ($now->gte($date)) {
                        array_push($resultAS, $id);
                    }
                }
            }

            if (count($resultVS) > 0 || count($resultVD) > 0 || count($resultAS) > 0 || count($resultAD) > 0) {
                DB::beginTransaction();
                try {
                    foreach ($resultVS as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['vote_state' => 1, 'updated_at' => $updatedAt]);
                        Redis::hdel('vote_start', $id);
                    }
                    foreach ($resultVD as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['vote_state' => -1, 'updated_at' => $updatedAt]);
                        Redis::hdel('vote_due', $id);
                    }
                    foreach ($resultAS as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['apply_state' => 1, 'updated_at' => $updatedAt]);
                        Redis::hdel('vote_apply_start', $id);
                    }
                    foreach ($resultAD as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['apply_state' => -1, 'updated_at' => $updatedAt]);
                        Redis::hdel('vote_apply_due', $id);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $log = new Logger('vote');
                    $log->pushHandler(new StreamHandler(storage_path('logs/vote.log'), Logger::INFO));
                    $log->addInfo('投票定时任务出现错误：'.$e);
                }
            }
        })->at("16:00")->everyThirtyMinutes()->withoutOverlapping()->before(function () {
            $log = new Logger('vote');
            $log->pushHandler(new StreamHandler(storage_path('logs/vote.log'), Logger::INFO));
            $log->addInfo('投票定时任务开始');
        })->after(function () {
            $log = new Logger('vote');
            $log->pushHandler(new StreamHandler(storage_path('logs/vote.log'), Logger::INFO));
            $log->addInfo('投票定时任务结束');
        });

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
