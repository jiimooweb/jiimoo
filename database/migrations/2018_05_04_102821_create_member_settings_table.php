<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->comment('小程序ID');
            $table->tinyInteger('status')->default(0)->comment('是否开启会员卡功能');            
            $table->integer('scale')->default(1)->comment('比例,默认为1');
            $table->tinyInteger('offer_status')->default(0)->comment('会员等级优惠状态，0：不开启，1：满减，2：折扣');
            $table->tinyInteger('auto_status')->default(0)->comment('结算时是否自动使用折扣优惠');
            $table->string('offer')->default('')->comment('会员等级对应的折扣优惠');
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
        Schema::dropIfExists('member_settings');
    }
}
