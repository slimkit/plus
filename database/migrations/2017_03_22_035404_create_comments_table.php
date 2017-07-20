<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->comment('Comment ID.');
            $table->integer('user_id')->unsigned()->comment('Send comment user.');
            $table->integer('target_user')->unsigned()->comment('Target user.');
            $table->integer('reply_user')->unsigned()->nullable()->default(0)->comment('Comments were answered.');
            $table->text('body')->comment('Comment body.');
            $table->morphs('commentable');
            $table->timestamps();

            $table->index('user_id');
            $table->index('target_user');
            $table->index('reply_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
