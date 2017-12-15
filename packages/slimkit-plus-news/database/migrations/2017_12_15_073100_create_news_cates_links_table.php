<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsCatesLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_cates_links', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->integer('news_id')->comment('资讯id');
            $table->integer('cate_id')->comment('分类id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_cates_links');
    }
}
