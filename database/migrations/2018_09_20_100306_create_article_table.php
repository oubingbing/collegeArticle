<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_articles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("poster_id")->index()->comment("发表人");
            $table->string('title')->default("")->comment("标题");
            $table->jsonb("cover_image")->nullable()->comment("文章封面图片");
            $table->longText("content")->comment("文章内容");

            $table->tinyInteger("type")->default(1)->comment("类型");
            $table->tinyInteger("status")->default(1)->commentA("状态");

            $table->timestamp('created_at')->nullable()->index()->comment("该记录创建的时间");
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
        Schema::dropIfExists('college_articles');
    }
}
