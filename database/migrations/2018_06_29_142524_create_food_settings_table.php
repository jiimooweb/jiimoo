<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->string('name', 30)->default('')->comment('店名');
            $table->string('notice', 200)->default('')->comment('公告');
            $table->tinyInteger('offer_status')->default(0)->comment('是否开启满减优惠：0：否，1：是');
            $table->string('offer')->default('')->comment('优惠内容');
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
        Schema::dropIfExists('food_settings');
    }
}
