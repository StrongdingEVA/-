<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string("username")->comment("用户昵称");
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->integer('mobile')->comment("用户手机号码")->default(0);
            $table->integer("user_point")->comment("用户可用积分")->default(0);
            $table->integer("level_point")->comment("用户等级积分")->default(0);
            $table->integer("post_count")->comment("用户发布文章次数")->default(0);
            $table->string("logo")->comment("头像")->default("/upload/userimg/default.png");
            $table->text("collections")->comment("用户收藏");
            $table->string("ip_addr")->comment("用户IP地址");
            $table->smallInteger("user_status")->comment("用户状态 0:正常，1禁止发言，2禁止发布文章，3禁止发言和发布文章，4静止登录")->default(0);
            $table->integer("valid_num")->comment("验证信息");
            $table->smallInteger("is_valid")->comment("是否验证 1 已验证，0 未验证")->default(0);
            $table->integer("num_send_time")->comment("验证码发送时间");
            $table->string("last_login")->comment("最后登录时间")->default("");
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
