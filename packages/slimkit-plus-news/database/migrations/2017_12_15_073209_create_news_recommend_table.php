<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsRecommendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_recommend', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('type')->comment('类型');
            $table->string('title')->nullable()->comment('推荐标题');
            $table->string('data')->nullable()->comment('管理数据');
            $table->integer('cate_id')->default(0)->comment('所属分类  0-推荐');
            $table->integer('cover')->comment('缩略图');
            $table->integer('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('news_recommend');
    }
}
