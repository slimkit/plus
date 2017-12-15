<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsPinnedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_pinneds', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('channel')->comment('频道: 资讯或资讯评论');
            $table->unsignedInteger('raw')->nullable()->default(0)->comment('如果存在则为评论id');
            $table->unsignedInteger('target')->comment('资讯id');
            $table->unsignedInteger('user_id')->comment('申请者id');
            $table->unsignedTinyInteger('state')->default(0)->comment('审核状态0: 待审核, 1审核通过, 2被拒');
            $table->unsignedInteger('target_user')->unllable()->default(0)->comment('资讯作者');
            $table->bigInteger('amount')->unsigned()->comment('金额');
            $table->unsignedInteger('day')->comment('天数');
            $table->unsignedInteger('cate_id')->nullable()->default(null)->comment('资讯置顶所属分类');
            $table->timestamp('expires_at')->nullable()->comment('到期时间，固定后设置该时间');
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
        Schema::dropIfExists('news_pinneds');
    }
}
