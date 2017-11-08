<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensitivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensitives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('word', 100)->comment('敏感词');
            $table->enum('type', ['replace', 'warning'])->comment('类型');
            $table->string('replace', 100)->nullable()->default(null)->comment('替换类型需要替换的文本');
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
        Schema::dropIfExists('sensitives');
    }
}
