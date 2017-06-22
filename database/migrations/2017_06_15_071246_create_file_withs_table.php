<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileWithsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_withs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unsigned()->comment('文件ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->string('channel', 100)->nullable()->default(null)->comment('记录频道');
            $table->string('raw', 100)->nullable()->default(null)->comment('原始频道关联信息');
            $table->string('size', 50)->nullable()->default(null)->comment('图片尺寸，目标文件如果是图片的话则存在。便于客户端提前预设盒子');
            $table->timestamps();
            $table->softDeletes();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table
                ->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->index('file_id');
            $table->index('user_id');
            $table->unique(['channel', 'raw', 'file_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_withs');
    }
}
