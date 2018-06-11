<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_id', 10)->default('')->comment('模板ID');
            $table->string('title', 20)->default('')->comment('模板标题');
            $table->string('keywords', 100)->default('')->comment('关键词');
            $table->string('keyword_id_list', 100)->default('')->comment('模板关键词列表');
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
        Schema::dropIfExists('notice_templates');
    }
}
