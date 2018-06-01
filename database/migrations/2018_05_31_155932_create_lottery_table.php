<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->string('name', 100)->comment('活动名');
            $table->date('start_time')->nullable()->comment('开始时间');
            $table->date('end_time')->nullable()->comment('结束时间');
            $table->tinyInteger('status')->default(0)->comment('是否举行，0：否,1：是');
            $table->integer('partake')->default(1)->comment('可参与次数');
            $table->timestamps();
        });

        Schema::create('lottery_fans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fan_id')->default(0)->comment('粉丝ID');
            $table->integer('get_prizes')->default(0)->comment('获得的奖品');
            $table->timestamps();
        });

        Schema::create('lottery_activity_fan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fan_id')->default(0)->comment('粉丝ID');
            $table->integer('activity_id')->default(0)->comment('活动ID');
            $table->integer('partook')->default(1)->comment('参与次数');
            $table->timestamps();
        });

        Schema::create('lottery_prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->default(0)->comment('活动ID');
            $table->integer('coupon_id')->default(0)->comment('优惠卷ID');
            $table->integer('probably')->default(1)->comment('概率(%)');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lottery_activities');
        Schema::dropIfExists('lottery_fans');
        Schema::dropIfExists('lottery_activity_fan');
        Schema::dropIfExists('lottery_prizes');
    }
}
