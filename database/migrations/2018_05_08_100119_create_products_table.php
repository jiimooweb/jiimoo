<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->integer('cate_id')->default(0)->comment('分类ID');            
            $table->string('name', 50)->default('')->comment('名称');    
            $table->string('thumb')->default('')->comment('封面图片');    
            $table->decimal('o_price', 8, 2)->default(0)->comment('原价');
            $table->decimal('c_price', 8, 2)->default(0)->comment('现价');
            $table->integer('stock')->default(0)->comment('库存');
            $table->tinyInteger('display')->default(0)->comment('是否上架，0：否，1：是');                
            $table->tinyInteger('hot')->default(0)->comment('是否热卖，0：否，1：是');            
            $table->tinyInteger('recommend')->default(0)->comment('是否力荐，0：否，1：是');            
            $table->text('format')->default('')->comment('配置');    
            $table->text('desc')->default('')->comment('详情介绍');    
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
        Schema::dropIfExists('products');
    }
}
