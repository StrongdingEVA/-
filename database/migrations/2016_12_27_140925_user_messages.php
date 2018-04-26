<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_messages', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id")->comment("用户ID");
            $table->integer("type")->comment("1有人回复你，2有人关注你，3有人@你")->default(1);
            $table->string("message_disc",100)->comment("信息描述");
            $table->integer("etc")->comment("拓展，文章ID等");
            $table->smallInteger("status",1)->comment("0 未读，1已读");
            $table->integer("comval");
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
        //
    }
}
