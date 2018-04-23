<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisplaysProductCatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('displays_product_cates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');            
            $table->string('name', 50)->default('')->comment('分类名称');
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
        Schema::dropIfExists('displays_product_cates');
    }
}
