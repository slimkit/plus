<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsCatesFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_cates_follow', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->integer('user_id')->comment('用户id');
            $table->string('follows')->nullable()->comment('关注的分类');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_cates_follow');
    }
}
