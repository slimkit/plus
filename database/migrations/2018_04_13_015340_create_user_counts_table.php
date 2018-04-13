<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_counts', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment('所有者 ID');
            $table->string('type', 100)->comment('统计类型');
            $table->integer('total')->nullable()->default(0)->comment('统计总数');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->primary(['user_id', 'type']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_counts');
    }
}
