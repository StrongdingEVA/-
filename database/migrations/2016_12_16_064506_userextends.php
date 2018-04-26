<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Userextends extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('userextends', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id")->comment("用户ID");
            $table->text("article_collection")->comment("用户收藏的帖子");
            $table->text("article_views")->comment("用户浏览的帖子");
            $table->text("user_foucs")->comment("用户关注的用户");
            $table->text("user_fans")->comment("用户的粉丝");
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
