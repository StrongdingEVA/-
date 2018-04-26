<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Categorys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('categories', function (Blueprint $table) {
            $table->increments("id");
            $table->string("category_name")->comment("文章分类名称");
            $table->smallInteger("level")->comment("分类等级");
            $table->string("path")->comment("分类path");
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
