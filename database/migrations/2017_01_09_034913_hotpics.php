<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Hotpics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('hotpics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string("disc",300)->comment("热图简介");
            $table->string('path')->comment("图片路径");
            $table->string('thumb')->comment("缩略图");
            $table->integer('like')->comment("点赞次数");
            $table->text('liker')->comment("点赞人");
            $table->integer('tehme')->comment("本期主题");
            $table->string('model')->comment("拍摄器材");
            $table->string('make')->comment("拍摄器材");
            $table->string('focallength')->comment("焦距");
            $table->string('longitude')->comment("经度");
            $table->string('latitude')->comment("经度");
            $table->string('fnumber')->comment("光圈");
            $table->string('addr')->comment("详细地址");
            $table->smallInteger('isshowparm')->comment("是否显示拍摄参数")->default(1);
            $table->smallInteger('iswinner')->comment("是否点赞最高")->default(0);
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
