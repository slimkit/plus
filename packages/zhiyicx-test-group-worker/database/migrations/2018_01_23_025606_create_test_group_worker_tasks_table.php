<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestGroupWorkerTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_group_worker_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('Task Title');
            $table->string('state', 100)->nullable()->default('WAIT')->comment('wait: 待审核、open: 开启、reject: 驳回、closed：关闭');
            $table->text('desc')->nullable()->comment('详情');
            $table->string('version')->comment('测试版本');
            $table->integer('assign')->unsgined()->nullable()->comment('任务指派人员');
            $table->dateTime('start_at')->comment('开始时间');
            $table->dateTime('end_at')->comment('结束时间');
            $table->integer('creator')->unsgined()->comment('任务创建者');
            $table->integer('project_id')->unsgined()->comment('任务 ID');
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
        Schema::dropIfExists('test_group_worker_tasks');
    }
}
