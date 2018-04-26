<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Answers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('answers', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("article_id")->comment("文章ID");
            $table->integer("comment_id")->comment("评论ID");
            $table->integer("from_user_id")->comment("来自ID");
            $table->integer("to_user_id")->comment("回复ID");
            $table->string("article_comment")->comment("回复内容");
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
