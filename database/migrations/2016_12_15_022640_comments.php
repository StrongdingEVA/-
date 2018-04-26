<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comments', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("article_id")->comment("文章ID");
            $table->integer("user_id")->comment("用户ID");
            $table->string("article_comment")->comment("评论内容");
            $table->integer("answer_count")->comment("被回复次数");
            $table->integer("good_count")->comment("被点赞次数");
            $table->integer("bad_count")->comment("被嘲讽次数");
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
