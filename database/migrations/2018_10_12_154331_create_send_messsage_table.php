<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendMesssageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mobile')->index()->default("")->comment('手机号码');
            $table->string('code')->index()->default('')->comment('验证码');
            $table->tinyInteger('status')->default(1)->comment('发送状态，1=成功，2=失败');

            $table->timestamp('expired_at')->nullable()->index()->comment('过期时间');

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
        Schema::dropIfExists('send_messages');
    }
}
