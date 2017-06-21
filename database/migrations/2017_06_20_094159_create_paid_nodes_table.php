<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_nodes', function (Blueprint $table) {
            $table->increments('id')->comment('付费记录ID');
            $table->string('index')->comment('付费索引');
            $table->string('subject')->comment('付费主题');
            $table->string('body')->comment('付费内容详情');
            $table->integer('amount')->unsigned()->comment('付费金额');
            $table->text('extra')->nullable()->default(null)->comment('拓展信息');
            $table->timestamps();

            $table->unique('index');
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
