<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet', function (Blueprint $table) {
            $table->integer('owner_id')->unsigned()->comment('钱包所属者');
            $table->bigInteger('balance')->unsigned()->comment('钱包余额');
            $table->integer('total_income')->unsigned()->comment('总收入');
            $table->integer('total_expenses')->unsigned()->comment('总支出');
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
        Schema::dropIfExists('wallet');
    }
}
