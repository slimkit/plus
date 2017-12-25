<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('版本类型');
            $table->string('version')->comment('版本名称');
            $table->integer('version_code')->comment('版本号');
            $table->text('description')->comment('更新说明');
            $table->string('link')->comment('链接地址');
            $table->tinyInteger('is_forced')->default(0)->comment('是否强制更新');
            $table->timestamps();

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
        Schema::dropIfExists('client_versions');
    }
}
