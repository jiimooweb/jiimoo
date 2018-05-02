<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->tinyInteger('type')->default(0)->comment('优惠券类型');
            $table->string('name', 50)->default('')->comment('名称');
            $table->string('module', 30)->default('')->comment('可用模块');            
            $table->decimal('use_price', 8, 2)->default(0)->comment('满足使用的金额');
            $table->decimal('price', 8, 2)->default(0)->comment('减少的金额');
            $table->string('thumb', 256)->default('')->comment('缩略图');
            $table->text('desc')->default('')->comment('说明');
            $table->integer('receive_num')->default(0)->comment('领取条件，如按积分领取');
            $table->tinyInteger('display')->default(0)->comment('是否在兑换中心显示,0：不显示，1：显示');
            $table->tinyInteger('time_type')->default(0)->comment('时间类型,0：固定时间段，1：用户领取后开始计算');
            $table->date('start_time')->nullable()->coment('开始时间');
            $table->date('end_time')->nullable()->coment('结束时间');
            $table->integer('startat')->default(0)->coment('领取后的生效天数');
            $table->integer('time_limit')->default(0)->coment('有效时间,单位：天');
            $table->integer('limit')->default(0)->comment('每个用户一共可领取数量');
            $table->integer('day_limit')->default(0)->comment('每人每日可领取数量');
            $table->integer('send_amount')->default(0)->comment('用户已领取的数量');            
            $table->integer('total')->default(0)->comment('代金券的总数');
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
        Schema::dropIfExists('coupons');
    }
}
