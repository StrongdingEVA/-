<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("category")->comment("文章分类ID",2);
            $table->string("article_title")->comment("文章标题",100);
            $table->string("article_disc")->comment("文章简介",300);
            $table->text("article_content")->comment("文章内容");
            $table->string("article_thumb")->comment("文章缩略图",100);
            $table->string("article_source_pic")->comment("文章原图",100);
            $table->integer("user_id")->comment("用户ID");
            $table->smallInteger("article_status")->comment("文章状态 1：删除 0：未删除");
            $table->smallInteger("is_show")->comment("是否显示 1：显示 0：不显示")->default(1);
            $table->integer("views")->comment("浏览次数");
            $table->integer("collections")->comment("被收藏次数");
            $table->text("collector")->comment("文章收藏者");
            $table->integer("comments")->comment("被评论次数");
            $table->string("article_video")->comment("视频地址");
            $table->smallInteger("is_recommend")->comment("1推荐 0不推荐");
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
