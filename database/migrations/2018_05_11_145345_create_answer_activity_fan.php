<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerActivityFan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('answers_activity_fan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->comment('小程序ID');
            $table->integer('fan_id')->comment('标题');
            $table->integer('partake')->comment('参加活动次数');
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
        //
        Schema::dropIfExists('answers_activity_fan');
    }
}
