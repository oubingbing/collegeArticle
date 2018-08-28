<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelPlanPointsTable extends Migration
{
    /**
     * 旅途预定的起点和终点
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_plan_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("travel_plan_id")->index()->comment("所属旅行");
            $table->string("address")->default('')->comment("地址");
            $table->string("address_detail")->default('')->commit("地址详情");
            $table->string("longitude",32)->default('')->comment("经度");
            $table->string("latitude",32)->default('')->comment("纬度");

            $table->tinyInteger("status")->default(0)->comment("状态");
            $table->tinyInteger("type")->default(1)->comment("站点类型，1=起点，2=终点");

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
        Schema::dropIfExists('travel_plan_points');
    }
}
