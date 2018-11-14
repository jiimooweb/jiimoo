<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * 
     */
    public function up()
    {
        Schema::create('reservation_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->string('name', 30)->default('')->comment('店名');
            $table->tinyInteger('status')->default(0)->comment('0，关店，1，正常营业');
            $table->string('aboutUs', 200)->default('')->comment('关于我们');
            $table->string('logoUrl', 512)->default('')->comment('封面');
            $table->string('address', 200)->default('')->comment('地址');
            $table->string('phone', 200)->default('')->comment('服务热线');
            $table->json('businessHours')->comment('营业时间');
            $table->tinyInteger('restInWeekend')->default(0)->comment('周六、周日不营业：0：否，1：是');
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
        Schema::dropIfExists('reservation_settings');
    }
}
