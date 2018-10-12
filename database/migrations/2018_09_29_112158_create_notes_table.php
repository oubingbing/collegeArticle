<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("category_id")->index()->comment("所属笔记本");
            $table->bigInteger("poster_id")->index()->comment("所属用户ID,网站后台用户");

            $table->string("title",128)->default("")->comment("笔记的标题");
            $table->longText("content")->comment("笔记内容");

            $table->jsonb("attachments")->comment("封面图片，暂时为空");

            $table->tinyInteger("use_type")->default(1)->comment("使用类型，1=日志，2=成长日志");
            $table->tinyInteger("type")->default(1)->comment("日记簿类型，1=公开，2=私密");
            $table->tinyInteger("status")->default(1)->comment("状态");

            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
