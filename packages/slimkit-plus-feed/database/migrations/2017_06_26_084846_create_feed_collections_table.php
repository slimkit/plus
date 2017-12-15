<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('收藏者UID');
            $table->integer('feed_id')->unsigned()->comment('动态id');
            $table->timestamps();

            $table->index('user_id');
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
        Schema::dropIfExists('feed_collections');
    }
}
