<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlacklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('black_lists', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment('main user');
            $table->integer('target_id')->unsigned()->comment('blacked user id');
            $table->timestamps();
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
        Schema::dropIfExists('black_lists');
    }
}
