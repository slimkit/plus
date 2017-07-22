<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('certification_name', 100);
            $table->integer('user_id')->unsigned();
            $table->text('data');
            $table->integer('examiner')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('certification_name');
            $table->unique(['user_id', 'certification_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certifications');
    }
}
