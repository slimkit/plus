<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('component')->comment('扩展包名');
            $table->string('comment_table')->comment('评论表名');
            $table->integer('comment_id')->comment('关联评论id');
            $table->text('comment_content')->comment('关联评论内容');
            $table->string('source_table')->comment('资源表名');
            $table->integer('source_id')->comment('关联资源id');
            $table->integer('source_cover')->default(0)->comment('资源封面');
            $table->longtext('source_content')->comment('资源内容');
            $table->integer('user_id')->default(0)->comment('评论者id');
            $table->integer('to_user_id')->default(0)->comment('资源作者id');
            $table->integer('reply_to_user_id')->default(0)->comment('被回复的评论者id');
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
        Schema::dropIfExists('comments');
    }
}
