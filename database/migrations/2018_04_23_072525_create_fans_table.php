<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->string('openid',32)->default('')->comment('openid');
            $table->string('unionid',100)->default('')->comment('unionid');
            $table->string('nickname',50)->default('')->comment('昵称');
            $table->tinyInteger('gender')->default(0)->comment('性别');
            $table->string('city',20)->default('')->comment('城市');
            $table->string('province',20)->default('')->comment('省份');
            $table->string('country',20)->default('')->comment('国家');
            $table->string('language',20)->default('')->comment('语言');
            $table->string('avatarUrl',512)->default('')->comment('头像');
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
        Schema::dropIfExists('fans');
    }
}
