<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');            
            $table->integer('user_id')->default(0)->comment('用户ID');            
            $table->integer('activity_id')->default(0)->comment('活动ID');            
            $table->tinyInteger('status')->default(0)->comment('审核状态，0为未审核，1为审核通过，-1为审核不通过');  
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
        Schema::dropIfExists('sign_lists');
    }
}
