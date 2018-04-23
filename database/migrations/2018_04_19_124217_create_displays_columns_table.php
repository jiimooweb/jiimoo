<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisplaysColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('displays_columns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');                                    
            $table->string('name', 50)->default('')->comment('专栏名称');
            $table->string('icon', 512)->default('')->comment('专栏图标');
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
        Schema::dropIfExists('displays_columns');
    }
}
