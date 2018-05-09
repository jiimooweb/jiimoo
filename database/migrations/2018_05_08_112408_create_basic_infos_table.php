<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->string('name', 50)->default('')->comment('企业名称');
            $table->string('logo', 512)->default('')->comment('企业logo');
            $table->string('intro', 512)->default('')->comment('简介');
            $table->string('tel', 30)->default('')->comment('电话联系');
            $table->string('qrcode', 512)->default('')->comment('二维码联系方式');
            $table->string('address', 100)->default('')->comment('地址');
            $table->text('desc')->default('')->comment('详情');
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
        Schema::dropIfExists('basic_infos');
    }
}
