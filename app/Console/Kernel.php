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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
//        $schedule->call(function (){
//            $updatedAt =  Carbon::now();
//            $now = new Carbon( Carbon::now()->format('Y-m-d H:i'));
//            $arr = Redis::hgetall('vote_start');
//            $result = [];
//            foreach ($arr as $id => $value ){
//                $date = new Carbon($value);
//                if($now->gte($date)){
//                    array_push($result,$id);
//                }
//            }
//            foreach ($result as $id){
//                 DB::table('votes_infos')->where('id',$id)->update(['vote_state'=>1,'updated_at'=>$updatedAt]);
//             }
//        })->everyMinute();

        $schedule->call(function () {
            $now = new Carbon(Carbon::now()->format('Y-m-d H:i'));
            $updatedAt = Carbon::now();
            $voteStart = Redis::hgetall('vote_start');
            $voteDue = Redis::hgetall('vote_due');
            $applyStart = Redis::hgetall('vote_apply_start');
            $applyDue = Redis::hgetall('vote_apply_due');
            //ID数组，V：投票；A：报名；S：开始；D：结束。
            $resultVS = [];
            $resultVD = [];
            $resultAS = [];
            $resultAD = [];
            foreach ($voteStart as $id => $value) {
                $date = new Carbon($value);
                if ($now->gte($date)) {
                    array_push($resultVS, $id);
                }
            }
            foreach ($voteDue as $id => $value) {
                $date = new Carbon($value);
                if ($now->gte($date)) {
                    array_push($resultVD, $id);
                }
            }
            foreach ($applyStart as $id => $value) {
                $date = new Carbon($value);
                if ($now->gte($date)) {
                    array_push($resultAS, $id);
                }
            }
            foreach ($applyDue as $id => $value) {
                $date = new Carbon($value);
                if ($now->gte($date)) {
                    array_push($resultAD, $id);
                }
            }

            if (count($resultVS) > 0||count($resultVD) > 0||count($resultAS) > 0||count($resultAD) > 0) {
                DB::beginTransaction();
                try {
                    foreach ($resultVS as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['vote_state' => 1, 'updated_at' => $updatedAt]);
                    }
                    foreach ($resultVD as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['vote_state' => -1, 'updated_at' => $updatedAt]);
                    }
                    foreach ($resultAS as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['vote_state' => 1, 'updated_at' => $updatedAt]);
                    }
                    foreach ($resultAD as $id) {
                        DB::table('votes_infos')->where('id', $id)->update(['vote_state' => -1, 'updated_at' => $updatedAt]);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
            }
        })->everyMinute();
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
