<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisplaysArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('displays_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');            
            $table->string('title', 256)->default('')->comment('文章标题');
            $table->string('author', 50)->default('')->comment('文章作者');
            $table->text('content')->default('')->comment('文章内容');       
            $table->integer('click')->default(0)->comment('文章点击数');
            $table->integer('cate_id')->default(0)->comment('文章分类ID');  
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
        Schema::dropIfExists('displays_articles');
    }
}
