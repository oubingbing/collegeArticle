<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * 旅途定位日志
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("user_id")->index()->comment("用户ID");
            $table->string("longitude",32)->default('')->comment("经度");
            $table->string("latitude",32)->default('')->comment("纬度");

            $table->timestamp('locate_at')->default('')->index()->comment("获取定位的时间");
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
        Schema::dropIfExists('locations');
    }
}
