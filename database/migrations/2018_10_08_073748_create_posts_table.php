<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('forum_id');
            $table->string('post_sort');
            $table->string('title');
            $table->string('content');
            $table->dateTime('create_time');
            $table->dateTime('update_time');
            $table->dateTime('delete_time');
            $table->string('is_delete')->default('no');
            $table->string('can_comment')->default('yes');
            $table->string('importment')->default('no');
            $table->integer('view_num')->default(0);
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
        Schema::drop('posts');
    }
}
