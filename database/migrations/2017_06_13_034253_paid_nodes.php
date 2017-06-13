<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaidNodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_nodes', function (Blueprint $table) {
            $table->string('node')->comment('节点');
            $table->string('name')->comment('节点名称');
            $table->tinyInteger('open')->nullable()->default(0)->comment('开关');
            $table->text('form')->nullable()->default(null)->comment('表单列表');
            $table->text('extra')->nullable()->default(null)->comment('节点额外数据');

            $table->timestamps();

            $table->primary('node');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paid_nodes');
    }
}
