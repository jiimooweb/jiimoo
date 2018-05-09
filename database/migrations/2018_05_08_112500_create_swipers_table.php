<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwipersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swipers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');            
            $table->string('image', 512)->default('')->comment('图片');
            $table->string('url', 512)->default('')->comment('链接');  
            $table->string('remake', 50)->default('')->comment('模块备注');    
            $table->tinyInteger('display')->default(0)->comment('是否显示'); 
            $table->integer('group')->default(0)->comment('轮播图组');            
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
        Schema::dropIfExists('swipers');
    }
}
