<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->string('order_no', 50)->unique()->default('')->comment('订单编号');                        
            $table->string('trans_no', 50)->unique()->default('')->comment('交易单号');                        
            $table->integer('fan_id')->default(0)->comment('粉丝ID');
            $table->decimal('price',8,2)->default(0)->comment('总价格');
            $table->tinyInteger('status')->default(0)->comment('0:未确认, 1：已确认, 2：已完成 , 4: 已支付，未确认');
            $table->string('prepay_id',100)->default('')->comment('订单微信支付的预订单id（用于发送模板消息）');    
            $table->time('pay_time')->default(0)->comment('支付时间');    
            $table->time('confirm_time')->default(0)->comment('确认时间');    
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
        Schema::dropIfExists('food_orders');
    }
}
