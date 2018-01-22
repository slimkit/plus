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
            $table->text('desc')->nullable()->comment('Project Desc');
            $table->string('branch')->comment('Project GitHub Repo Upload File Branch');
            $table->string('github_owner')->comment('Project A user or organization account');
            $table->string('github_repo')->comment('Project A repository name');
            $table->integer('issues_count')->unsgined()->nullable()->default(0)->comment('Issues Count');
            $table->integer('closed_issues_count')->unsgined()->nullable()->default(0)->comment('Closed Issess Count');
            $table->integer('task_count')->unsgined()->nullable()->default(0)->comment('Test Task Count');
            $table->integer('task_completed_count')->unsgined()->nullable()->default(0)->comment('Test Task Completed Count');
            $table->integer('creator')->unsgined()->comment('Project Creator');
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
