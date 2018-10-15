<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operate_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("obj_id")->index()->comment("计数对象");
            $table->tinyInteger("type")->default(1)->comment("计数的类型，1=作者，2=笔记簿，3=笔记");

            $table->integer("follow")->default(0)->comment("关注计数");
            $table->integer("collect")->default(0)->comment("收藏计数");
            $table->integer("praise")->default(0)->comment("点赞计数");
            $table->integer("view")->default(0)->comment("浏览次数");

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
        Schema::dropIfExists('operate_statistics');
    }
}
