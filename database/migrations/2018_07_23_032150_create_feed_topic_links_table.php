<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTopicLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_topic_links', function (Blueprint $table) {
            $table->integer('topic_id')->unsigned()->comment('Topic ID');
            $table->integer('feed_id')->unsigned()->comment('Feed ID');

            $table->primary(['topic_id', 'feed_id']);
            $table->index('topic_id');
            $table->index('feed_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_topic_links');
    }
}
