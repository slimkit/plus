<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_reports', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id')
                ->unsigned()
                ->comment('举报用户');
            $table
                ->integer('target_id')
                ->unsigned()
                ->comment('被举报用户');
            $table
                ->integer('group_id')
                ->unsigned()
                ->comment('所属圈子');
            $table->integer('resource_id')
                ->unsigned()
                ->comment('被举报的资源id');
            $table
                ->text('content')
                ->comment('举报内容');
            $table->string('type')
                ->default('post')
                ->comment('举报类型：post or comment');
            $table->tinyInteger('status')
                ->unsigned()
                ->default(0)
                ->comment('0-未处理 1-已处理 2-被驳回');
            $table->string('cause')
                ->nullable()
                ->comment('被驳回的原因');
            $table->integer('handler')
                ->unsigned()
                ->nullable()
                ->comment('审核处理人');
            $table->timestamps();

            $table->index('status');
            $table->index('group_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_reports');
    }
}
