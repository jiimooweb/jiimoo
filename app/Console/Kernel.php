<?php

namespace App\Console;

use App\Models\Votes\Info;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function (){
            $now = new Carbon( Carbon::now()->format('Y-m-d H:i'));
            $arr = Redis::hgetall('vote_start');
            $result = [];
            foreach ($arr as $id => $value ){
                $date = new Carbon($value);
                if($now->gte($date)){
                    array_push($result,$id);
                }
            }
            foreach ($result as $id){
                 DB::table('votes_infos')->where('id',$id)->update(['vote_state'=>1]);
             }


//            DB::table('votes_infos')->where('id',32)->update(['description'=>$a]);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * 自定义function
     * 依传入参数查询redis，回传日期大于当前日期的ID结果集
     * @param string $str
     * @return array $result
     */
    protected function taskVotes($str){
        $now = new Carbon( Carbon::now()->format('Y-m-d H:i'));
        $arr = Redis::hgetall($str);
        $result = [];
        foreach ($arr as $id => $value ){
            $date = new Carbon($value);
            if($now->gte($date)){
                array_push($result,$id);
            }
        }
        return $result;
    }
}
