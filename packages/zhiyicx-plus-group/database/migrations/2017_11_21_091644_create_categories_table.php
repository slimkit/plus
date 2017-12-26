<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('类别名称');
            $table->integer('sort_by')->nullable()->default(1000)->comment('排序值');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('启用状态 0-启用 1-禁用');
            $table->timestamps();

            $table->unique('name');
            $table->index('sort_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_categories');
    }
}
