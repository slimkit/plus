<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestGroupWorkerAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_group_worker_accesses', function (Blueprint $table) {
            $table->integer('owner')->unsgined()->comment('The Access be User');
            $table->string('login')->comment('GitHub Access Username');
            $table->string('password')->comment('GitHub Access Password');
            $table->timestamps();
            $table->primary('owner');
            $table->unique('login');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_group_worker_accesses');
    }
}
