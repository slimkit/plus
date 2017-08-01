<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Advertising extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('space_id')->unsigned()->comment('广告位id');
            $table->string('title')->comment('广告标题');
            $table->string('type')->comment('类型');
            $table->text('data')->nullable()->comment('相关参数');
            $table->integer('sort')->default(0)->comment('广告位排序');
            $table->timestamps();

            $table->index('space_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertising');
    }
}
