<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersActivitiesDepot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('answers_activity_depot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->comment('活动id');
            $table->integer('depot_id')->comment('题库ID');
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
        //
        Schema::dropIfExists('answers_activity_depot');
    }
}
