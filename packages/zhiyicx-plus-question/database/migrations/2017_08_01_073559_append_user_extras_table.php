<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendUserExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('user_extras', 'questions_count')) {
            Schema::table('user_extras', function (Blueprint $table) {
                $table->integer('questions_count')->unsigned()->nullable()->default(0)->comment('用户提问数统计');
            });
        }

        if (! Schema::hasColumn('user_extras', 'answers_count')) {
            Schema::table('user_extras', function (Blueprint $table) {
                $table->integer('answers_count')->unsigned()->nullable()->default(0)->comment('用户回答数统计');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_extras', 'questions_count')) {
            Schema::table('user_extras', function (Blueprint $table) {
                $table->dropColumn('questions_count');
            });
        }

        if (Schema::hasColumn('user_extras', 'answers_count')) {
            Schema::table('user_extras', function (Blueprint $table) {
                $table->dropColumn('answers_count');
            });
        }
    }
}
