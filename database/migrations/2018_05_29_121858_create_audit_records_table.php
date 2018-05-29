<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xcx_id')->default(0)->comment('小程序ID');
            $table->integer('template_id')->default(0)->comment('模板ID');      
            $table->integer('audit_id')->default(0)->comment('审核ID');
            $table->tinyInteger('status')->default(2)->comment('审核状态，其中0为审核成功，1为审核失败，2为审核中'); 
            $table->string('org_id')->default('')->comment('小程序的原始ID');     
            $table->string('sys_id')->default('')->comment('发送方帐号（一个OpenID，此时发送方是系统帐号）');
            $table->text('reason')->default('')->comment('审核失败的原因');
            $table->integer('succ_time')->default(0)->comment('成功时间');
            $table->integer('fail_time')->default(0)->comment('失败时间');
            $table->integer('create_time')->default(0)->comment('创建时间');
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
        Schema::dropIfExists('audit_records');
    }
}
