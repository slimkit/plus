<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensitiveWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensitive_words', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('敏感词名称');
            $table->string('replace_name')->nullable()->comment('替换名称');
            $table->unsignedInteger('filter_word_category_id')->comment('栏目id');
            $table->unsignedInteger('filter_word_type_id')->comment('类别id');
            $table->unsignedInteger('user_id')->comment('创建人');
            $table->softDeletes();
            $table->timestamps();

            $table->index('filter_word_category_id');
            $table->index('filter_word_type_id');
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
        Schema::dropIfExists('sensitive_words');
    }
}
