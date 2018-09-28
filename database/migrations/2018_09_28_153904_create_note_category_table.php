<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("web_user_id")->index()->comment("所属用户ID,网站后台用户");
            $table->string("name",128)->default("")->comment("日记簿名字");

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
        Schema::dropIfExists('note_categories');
    }
}
