<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTopicFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_topic_followers', function (Blueprint $table) {
            $table->increments('index')->comment('The topic followers index');
            $table->integer('topic_id')->unsigned()->comment('Be follow topic id');
            $table->integer('user_id')->unsigned()->comment('Follow topic user id');
            $table->timestamps();

            $table->unique(['topic_id', 'user_id']);
            $table->index('user_id');
            $table->index('topic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_topic_followers');
    }
}
