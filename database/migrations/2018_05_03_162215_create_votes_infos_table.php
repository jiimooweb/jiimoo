<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes_infos', function (Blueprint $table) {
            //基础
            $table->increments('id');
            $table->integer('xcx_id')->comment('小程序ID');
            $table->integer("create_id")->comment('创建者');
            $table->string('title',100)->comment('标题');
            $table->text('description')->comment('描述');
            $table->dateTime('vote_start_date')->comment('投票开始时间');
            $table->dateTime('vote_due_date')->comment('投票截止时间');
            $table->tinyInteger('type')->comment('投票。0:活动。1:一般');
            $table->tinyInteger('share')->comment('分享。0:禁止。1:公开');
            $table->tinyInteger('vote_state')->default(0)->comment('状态。0:未开始，1:已开始。-1:已结束。');
            //防刷票
            $table->tinyInteger('cycle')->comment('周期。0：活动时间可投票数唯一。1：每天恢复可投票数。');
            $table->integer("num")->comment('可投票数');
            $table->integer('limit')->comment('选项可投上限');

            //一般
            $table->json('options')->nullable()->comment('选项');
            //报名
            $table->dateTime('apply_start_date')->nullable()->comment('报名开始时间');
            $table->dateTime('apply_due_date')->nullable()->comment('报名截止时间');
            $table->tinyInteger('is_check')->nullable()->comment('报名是否需审核。0:不需要。1:需要。');
            $table->tinyInteger('apply_state')->nullable()->default(0)->comment('状态。0:未开始，1:已开始。-1:已结束。');

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
        Schema::dropIfExists('votes_infos');
    }
}
