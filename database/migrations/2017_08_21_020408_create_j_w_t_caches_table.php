<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJWTCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jwt_caches', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment('User ID.');
            $table->string('key');
            $table->text('value');
            $table->integer('minutes');
            $table->tinyInteger('status')->nullable()->default(0);
            $table->timestamps();

            $table->index('key');
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
        Schema::dropIfExists('jwt_caches');
    }
}
