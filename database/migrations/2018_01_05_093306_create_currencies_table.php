<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->integer('owner_id')->unsigned()->comment('货币所属者');
            $table->integer('type')->unsigned()->comment('货币类型');
            $table->integer('sum')->comment('货币总和');
            $table->timestamps();
            $table->primary(['owner_id', 'type']);
            $table->index('owner_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
