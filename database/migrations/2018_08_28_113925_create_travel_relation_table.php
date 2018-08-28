<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelRelationTable extends Migration
{
    /**
     * 旅途关联的用户
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("travel_plan_id")->index()->comment("关联的旅途");
            $table->bigInteger("user_id")->index()->comment("关联的用户");

            $table->tinyInteger("status")->default(1)->comment("关联的状态，1=关联中，2=关联解除");

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
        Schema::dropIfExists('travel_relations');
    }
}
