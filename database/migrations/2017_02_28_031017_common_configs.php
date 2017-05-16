<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommonConfigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_configs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('name', 30)->comment('配置名称');
            $table->string('namespace', 15)->comment('配置命名空间');
            $table->text('value')->nullable()->comment('缓存值');
            $table->timestamps();
            $table->primary(['name', 'namespace']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common_configs');
    }
}
