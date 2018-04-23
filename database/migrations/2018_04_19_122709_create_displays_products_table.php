<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisplaysProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('displays_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');            
            $table->string('name',50)->default('')->comment('产品名称');
            $table->decimal('price',10,2)->default(0)->comment('产品价格');
            $table->text('desc')->default(0)->comment('产品简介');
            $table->integer('cate_id')->default(0)->comment('分类ID');
            $table->tinyInteger('display')->default(0)->comment('是否显示');
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
        Schema::dropIfExists('displays_products');
    }
}
