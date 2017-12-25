<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->unsigned()->comment('所属问题');
            $table->integer('user_id')->unsigned()->comment('用户');
            $table->text('body')->nullable()->default(null)->comment('回答内容');
            $table->tinyInteger('anonymity')->unsigned()->nullable()->default(0)->comment('是否作者匿名');
            $table->tinyInteger('adoption')->nullable()->default(0)->comment('是否被采纳');
            $table->tinyInteger('invited')->nullable()->default(0)->comment('是否是被邀请回答');
            $table->integer('comments_count')->nullable()->default(0)->comment('问题评论统计');
            $table->bigInteger('rewards_amount')->unsigned()->nullable()->default(0)->comment('打赏总额');
            $table->integer('rewarder_count')->unsigned()->nullable()->default(0)->comment('打赏的人统计');
            $table->integer('likes_count')->nullable()->default(0)->comment('喜欢数量统计');
            $table->integer('views_count')->unsigned()->nullable()->default(0)->comment('答案查看数量统计');
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
        Schema::dropIfExists('answers');
    }
}
