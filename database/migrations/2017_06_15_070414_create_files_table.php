<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 150)->comment('文件 hash');
            $table->string('origin_filename')->nullbale()->default(null)->comment('原始文件名');
            $table->string('filename')->comment('文件名');
            $table->string('mime', 100)->comment('mime type');
            $table->float('width')->nullbale()->default(null)->comment('图片宽度');
            $table->float('height')->nullbale()->default(null)->comment('图片高度');
            $table->timestamps();

            $table->unique('hash');
            $table->index('mime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
