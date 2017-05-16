<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            // 创建表结构
            $table->increments('id');
            $table->string('origin_filename')->comment('原始文件名');
            $table->string('filename')->comment('文件名称（完整路径）');
            $table->string('hash', 100)->comment('文件hash值');
            $table->string('mime', 100)->comment('文件mime');
            $table->string('extension', 10)->comment('文件拓展名称');
            $table->float('image_width')->nullable()->comment('图片宽度');
            $table->float('image_height')->nullable()->comment('图片高度');
            $table->timestamps();
            $table->softDeletes();
            // 设置表引擎
            $table->engine = 'InnoDB';
            // 建立索引
            $table->unique('hash');
            $table->index('mime', 'extension');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storages');
    }
}
