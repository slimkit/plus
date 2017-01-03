<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->comment('原文件名');
            $table->string('path')->comment('存储路径');
            $table->string('hash')->uniqid()->comment('文件hash值');
            $table->string('mime')->comment('mime类型');
            $table->string('extension')->comment('文件扩展名');
            $table->integer('width')->nullable()->default(0)->comment('图片宽度');
            $table->integer('height')->nullable()->default(0)->comment('图片高度');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachs');
    }
}
