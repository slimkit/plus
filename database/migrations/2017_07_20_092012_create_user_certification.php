<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCertification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_certifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('certification')->comment('type of certification');
            $table->text('data')->comment('certification data');
            $table->unsignedTinyInteger('status')->default(0)->comment('status of certification');
            $table->unsignedInteger('user_id')->unique()->comment('who post certification');
            $table->unsignedInteger('uid')->comment('who did certificate');
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
        Schema::dropIfExists('user_certifications');
    }
}
