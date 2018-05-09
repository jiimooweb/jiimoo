<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activitys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');                      
            $table->string('name', 50)->default('')->comment('活动名称');
            $table->text('content')->default('')->comment('活动内容');
            $table->integer('places')->default(0)->comment('活动名额，默认0为不限人数');
            $table->integer('sign_type')->default(0)->comment('活动报名类型，0为免费，1为收费');
            $table->decimal('sign_price')->default(0)->comment('报名金额'); 
            $table->time('sign_start_time')->default(null)->coment('报名开始时间');
            $table->time('sign_end_time')->default(null)->coment('报名结束时间');
            $table->time('start_time')->default(null)->coment('活动开始时间');
            $table->time('end_time')->default(null)->coment('活动结束时间');
            $table->tinyInteger('status')->default(0)->comment('活动状态，0为未开始，1为开始，-1为已结束');
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
        Schema::dropIfExists('activitys');
    }
}
