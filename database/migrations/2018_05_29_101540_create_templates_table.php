<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->default(0)->comment('模板ID');
            $table->string('title', 30)->default('')->comment('标题');
            $table->string('thumb')->default('')->comment('首页');
            $table->string('qrcode')->default('')->comment('二维码');
            $table->string('desc', 100)->default('')->comment('简述');
            $table->string('version')->default('')->comment('版本号');
            $table->string('ext_json')->default('')->comment('配置文件内容');
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
        Schema::dropIfExists('templates');
    }
}
