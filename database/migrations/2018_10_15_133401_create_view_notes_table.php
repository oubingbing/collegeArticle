<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("obj_id")->index()->comment("被浏览的对象");
            $table->bigInteger("user_id")->index()->comment("浏览人");

            $table->tinyInteger("type")->default(0)->comment("点赞的类型,1=作者，2=笔记簿，3=文章");

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
        Schema::dropIfExists('view_notes');
    }
}
