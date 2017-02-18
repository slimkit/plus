<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Menus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id')->comment('key');
            $table->string('name')->comment('The menu name.');
            $table->string('route')->comment('The menu route name.');
            $table->string('type')->comment('The menu type.');
            $table->string('icon')->nullable()->default('')->comment('The menu icon.');
            $table->string('ext')->nullable()->default('')->comment('The menu ext info');
            $table->timestamps();

            $table->index('name');
            $table->index('type');
            $table->index('ext');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
