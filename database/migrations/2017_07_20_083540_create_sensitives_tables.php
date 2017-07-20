<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensitivesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensitives', function (Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->string('sensitive', 20)->comment('sensitive word');
            $table->timestamps();
            $table->index('sensitive');
            $table->unique('sensitive');
        });
    }

    /**
     * delete table.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensitives');
    }
}
