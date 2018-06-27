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
            $voteStart = self::taskVotes('vote_start');
            $voteDue = self::taskVotes('vote_due');
            $applyStart = self::taskVotes('vote_apply_start');
            $applyDue = self::taskVotes('vote_apply_due');
            if(count($voteStart)>0||count($voteDue)>0||count($applyStart)>0||count($applyDue)>0){
                DB::beginTransaction();
                try{
                    foreach ($voteStart as $id){
                        Info::where('id',$id)->update(['vote_state'=>'1']);
                        Redis::hdel('vote_start',$id);
                    }
                    foreach ($voteDue as $id){
                        Info::where('id',$id)->update(['vote_state'=>'-1']);
                        Redis::hdel('vote_due',$id);
                    }
                    foreach ($applyStart as $id){
                        Info::where('id',$id)->update(['apply_state'=>'-1']);
                        Redis::hdel('vote_apply_start',$id);
                    }
                    foreach ($applyDue as $id){
                        Info::where('id',$id)->update(['apply_state'=>'-1']);
                        Redis::hdel('vote_apply_due',$id);
                    }
                    DB::commit();
                }catch (\Exception $e){
                    DB::rollBack();
                }
            }
        })->everyThirtyMinutes()->withoutOverlapping();;
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
            if($date->gte($now)){
                array_push($result,$id);
            }
        }
        return $result;
    }
}
