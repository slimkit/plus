<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StorageTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 100)->comment('文件hash');
            $table->string('origin_filename')->comment('原始文件名');
            $table->string('filename')->comment('文件名');
            $table->float('width')->nullable()->comment('宽度');
            $table->float('height')->nullable()->comment('高度');
            $table->string('mime_type')->comment('文件类型');
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->index(['hash']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_tasks');
    }
}
