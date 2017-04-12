<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiggs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diggs', function (Blueprint $table) {
            $table->increments('id');
            $table->engine = 'InnoDB';
            $table->string('component')->comment('扩展包名');
            $table->string('digg_table')->comment('点赞表名');
            $table->integer('digg_id')->comment('关联点赞记录id');
            $table->string('source_table')->comment('资源表名');
            $table->integer('source_id')->comment('关联资源id');
            $table->integer('source_cover')->default(0)->comment('资源封面');
            $table->longtext('source_content')->comment('资源内容');
            $table->integer('user_id')->default(0)->comment('点赞者id');
            $table->integer('to_user_id')->default(0)->comment('资源作者id');
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
        Schema::dropIfExists('diggs');
    }
}
