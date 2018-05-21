<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNativePayOrderPayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::hasTable('native_pay_orders', function(Blueprint $table) {
            $table->renameColumn('out-trade-no', 'out_trade_no');
            $table->renameColumn('trade-no', 'trade_no');
            $table->unsignedTinyInteger('from')->comment('来自那个客户端');
            $table->dropIndex('out-trade-no');
            $table->dropIndex('trade_no');
            $table->index('out_trade_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('native_pay_orders');
    }
}
