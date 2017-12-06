<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->integer('target_user')->unsigned()->comment('对方用户ID');
            $table->morphs('reportable');
            $table->tinyInteger('status')->default(0)->comment('处理状态 0-待处理 1-已处理 2-已驳回');
            $table->string('reason')->nullable()->comment('举报理由');
            $table->string('mark')->nullable()->comment('处理标记');
            $table->timestamps();

            $table->index('user_id');
            $table->index('target_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
