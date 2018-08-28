<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelPlanTable extends Migration
{
    /**
     * 旅途计划
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("user_id")->index()->comment("用户ID");
            $table->string("title",128)->default("")->comment("旅行标题");

            $table->tinyInteger("status")->default(0)->comment("旅行状态，0=初始状态，1=旅行中");
            $table->tinyInteger("type")->default(1)->comment("类型,1=旅途，2=日常");

            $table->timestamp('created_at')->default('')->index()->comment("该记录创建的时间");
            $table->timestamp('updated_at')->default('');
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
        Schema::dropIfExists('travel_plans');
    }
}
