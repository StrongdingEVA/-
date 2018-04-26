<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pointrecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pointrecords', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id")->comment("用户ID");
            $table->integer("type")->comment("1增加-1减少")->default(1);
            $table->integer("point")->comment("积分");
            $table->string("describe",100)->comment("描述");
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
