<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Sendrecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sendrecords', function (Blueprint $table) {
            $table->increments("id");
            $table->string("email",50)->comment("邮箱");
            $table->char("number",4)->comment("验证码");
            $table->smallInteger("is_check")->default(0)->comment("0未验证，1已验证");
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
