<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->integer('cate_id')->default(0)->comment('分类ID');            
            $table->string('name', 30)->default('')->comment('名称');    
            $table->string('thumb', 512)->default('')->comment('菜品图片');    
            $table->decimal('o_price', 8, 2)->default(0)->comment('原价');
            $table->decimal('c_price', 8, 2)->default(0)->comment('现价');
            $table->tinyInteger('hot')->default(0)->comment('是否热卖，0：否，1：是');            
            $table->tinyInteger('recommend')->default(0)->comment('是否力荐，0：否，1：是');            
            $table->tinyInteger('display')->default(0)->comment('是否上架，0：否，1：是');    
            $table->string('format', 30)->default('')->comment('规格');    
            $table->string('category', 30)->default('')->comment('品类');    
            $table->string('desc', 256)->default('')->comment('菜品介绍');    
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
        Schema::dropIfExists('foods');
    }
}
