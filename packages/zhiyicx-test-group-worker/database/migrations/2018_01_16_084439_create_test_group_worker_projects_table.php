<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestGroupWorkerProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_group_worker_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Project Name');
            $table->string('github')->comment('Project GitHub Repo URL');
            $table->integer('issues_count')->unsgined()->comment('Issues Count');
            $table->integer('task_count')->unsgined()->comment('Test Task Count');
            $table->integer('task_completed_count')->unsgined()->nullable()->default(0)->comment('Test Task Completed Count');
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
        Schema::dropIfExists('test_group_worker_projects');
    }
}
