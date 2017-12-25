<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id')->comment('问题ID');
            $table->integer('user_id')->unsigned()->comment('问题发布者');
            $table->string('subject')->comment('问题主题');
            $table->text('body')->nullable()->default(null)->comment('问题详情');
            $table->tinyInteger('anonymity')->unsigned()->nullable()->default(0)->comment('是否作者匿名');
            $table->integer('amount')->unsigned()->nullable()->default(0)->comment('悬赏总额，如果为 0，则不走账务流程。');
            $table->tinyInteger('automaticity')->unsigned()->nullable()->default(0)->comment('回答自动入帐');
            $table->tinyInteger('look')->unsigned()->nullable()->default(0)->comment('是否围观');
            $table->tinyInteger('excellent')->unsigned()->nullable()->default(0)->comment('是否是杰出的，精华。');
            $table->tinyInteger('status')->nullable()->default(0)->comment('是否已解决，0 - 未解决，1 - 已解决， 2 - 问题关闭');
            $table->integer('comments_count')->nullable()->default(0)->comment('问题评论统计');
            $table->integer('answers_count')->nullable()->default(0)->comment('答案数量统计');
            $table->integer('watchers_count')->nullable()->default(0)->comment('关注者统计');
            $table->integer('likes_count')->nullable()->default(0)->comment('喜欢数量统计');
            $table->integer('views_count')->unsigned()->nullable()->default(0)->comment('查看统计');
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
        Schema::dropIfExists('questions');
    }
}
