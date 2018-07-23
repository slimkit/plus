<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('The topic name');
            $table->integer('logo')->unsigned()->nullable()->default(0)->comment('The topic logo, file with id');
            $table->text('desc')->nullable()->comment('The topic desc');
            $table->integer('creator_user_id')->unsigned()->comment('The topic creator user ID');
            $table->timestamps();

            $table->unique('name');
            $table->index('creator_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_topics');
    }
}
