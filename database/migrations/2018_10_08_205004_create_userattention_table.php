<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserattentionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userattentions',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('forum_id');
            $table->integer('experience');
            $table->string('active');
            $table->string('admin');
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
        Schema::drop('userattentions');
    }
}
