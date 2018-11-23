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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('sex')->defult('null');
            $table->string('birthday')->defult('2000-01-01');
            $table->string('telephone')->defult('null');
            $table->string('area')->defult('北京');
            $table->string('active')->defult('yes');
            $table->string('admin')->defult('no');
            $table->string('user_image')->defult('/user/image/defult.jpg');
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
