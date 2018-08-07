<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace', 150)->comment('配置命名空间');
            $table->string('name', 150)->comment('配置名称');
            $table->text('contents')->nullable()->comment('配置数据');
            $table->timestamps();

            $table->index('namespace');
            $table->index('name');
            $table->unique(['namespace', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
