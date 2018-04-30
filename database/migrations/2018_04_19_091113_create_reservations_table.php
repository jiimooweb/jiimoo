<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('xcx_id')->comment('小程序ID');
            $table->string('reservation_name')->comment('预约名称');
            $table->string('keyword')->comment('关键字');
            $table->string('prompt')->comment('提交成功提示');
            $table->json('content')->comment('预约内容');
            $table->timestamp('started_at')->nullable()->comment('开始时间');
            $table->timestamp('termination_at')->nullable()->comment('结束时间');
            $table->string('state')->comment('状态');
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
        Schema::dropIfExists('reservations');
    }
}
