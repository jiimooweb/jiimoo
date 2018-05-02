<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->integer('fan_id')->default(0)->comment('粉丝ID');
            $table->integer('coupon_id')->default(0)->comment('优惠券ID');
            $table->date('start_time')->coment('开始时间');
            $table->date('end_time')->coment('结束时间');
            $table->tinyInteger('status')->default(0)->comment('是否使用，0：未使用，-1：已过期，1：已使用');
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
        Schema::dropIfExists('coupon_records');
    }
}
