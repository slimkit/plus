<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupRecommendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_recommends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->comment('所属圈子');
            $table->integer('referrer')->unsigned()->comment('推荐人');
            $table->tinyInteger('disable')->unsigned()->default(0)->comment('0-启用 1-禁用');
            $table->integer('sort_by')->nullable()->default(1000)->comment('排序值');
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
        Schema::dropIfExists('group_recommends');
    }
}
