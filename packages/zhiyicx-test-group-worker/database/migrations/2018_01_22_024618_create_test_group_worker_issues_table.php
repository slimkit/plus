<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestGroupWorkerIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_group_worker_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsgined()->comment('Project ID');
            $table->integer('github_issue_id')->unsgined()->comment('GitHub Issue ID');
            $table->string('title')->comment('Issue Title');
            $table->integer('creator')->comment('The Issue Creator');
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
        Schema::dropIfExists('test_group_worker_issues');
    }
}
