<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImCroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('im_group', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('表ID');
            $table->integer('im_group_id')->unique()->nullable()->default(0)->comment('环信群组ID');
            $table->integer('user_id')->unique()->nullable()->default(0)->comment('用户ID');
            $table->string('group_face')->nullable()->default(null)->comment('群组头像');
            $table->tinyInteger('type')->nullable()->default(0)->comment('类型：0-群组 1-聊天室');
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
        Schema::dropIfExists('im_group');
    }
}
