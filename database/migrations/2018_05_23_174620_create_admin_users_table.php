<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->default('')->comment('用户微信昵称');
            $table->string('avatar')->default('')->comment('用户头像');
            $table->string('email')->unique()->index()->comment('邮箱');
            $table->string('password')->default('')->comment('预留账号密码');
            $table->string('mobile')->default('')->index()->comment('预留手机号码字段');
            $table->string('active_token')->default('')->comment('账号激活码');
            $table->timestamp('token_expire')->nullable()->comment('激活码失效时间');
            $table->tinyInteger('status')->default(0)->comment('用户状态，0未激活，1=已激活');
            $table->string('remember_token')->nullable('')->comment('记住登录');

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
        Schema::dropIfExists('admin_users');
    }
}
