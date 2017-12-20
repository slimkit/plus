<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAroundAmapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('around_amaps', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment('作者UID');
            $table->string('longitude')->comment('精度');
            $table->string('latitude')->comment('维度');
            $table->integer('_id')->unsigned()->comment('高德地图数据id');

            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('around_amaps');
    }
}
