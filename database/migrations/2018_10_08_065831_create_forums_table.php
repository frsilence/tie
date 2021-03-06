<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forums',function(Blueprint $table){
            $table->increments('id');
            $table->string('forum_name');
            $table->string('forum_description');
            $table->string('forum_sort');
            $table->integer('user_id');
            $table->dateTime('create_time');
            $table->integer('create_user');
            $table->string('sort');
            $table->string('active');
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
        Schema::drop('forums');
    }
}
