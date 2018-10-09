<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 50)->comment('博客自定义地址');
            $table->string('name', 100)->comment('博客名称');
            $table->string('desc', 255)->nullable()->default(null)->comment('博客描述');
            $table->string('logo', 255)->nullable()->default(null)->comment('博客 Logo');
            $table->integer('owner_id')->unsigned()->comment('博客所有者');
            $table->integer('posts_count')->unsigned()->nullable()->default(0)->comment('博客帖子统计');
            $table->timestamp('latest_post_sent_at')->nullable()->default(null)->comment('最后发布文章时间');
            $table->timestamp('reviewed_at')->nullable()->default(null)->comment('审核通过时间');
            $table->timestamps();

            // 索引
            $table->unique('slug');
            $table->unique('owner_id');
            $table->index('posts_count');
            $table->index('latest_post_sent_at');
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
