<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilterWordTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_word_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('过滤词类别名称');
            $table->unsignedTinyInteger('status')->default(1)->comment('开启状态：0-关闭，1-开启');

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filter_word_types');
    }
}
