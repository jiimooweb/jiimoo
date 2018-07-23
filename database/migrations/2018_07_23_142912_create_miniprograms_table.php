<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiniprogramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miniprograms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 30)->default('')->comment('标题');
            $table->string('thumb')->default('')->comment('封面');
            $table->string('price')->default('')->comment('价格');
            $table->string('app_id')->default('')->comment('小程序的APPID');
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
        Schema::dropIfExists('miniprograms');
    }
}
