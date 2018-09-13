<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLocationLogTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan make:migration update_location_log_table --table=location_logs --path=/database/migrations/modify
     * php artisan migrate --path=/database/migrations/modify
     *
     * @return void
     */
    public function up()
    {
        Schema::table('location_logs', function (Blueprint $table) {
            $table->string("address_format")->default("")->comment("格式化的地理名字");
            $table->string("address")->default("")->comment("地址");
            $table->string("province")->default("")->comment("省份");
            $table->string("city")->default("")->comment("市");
            $table->string("district")->default("")->comment("街道");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location_logs', function (Blueprint $table) {
            //
        });
    }
}
