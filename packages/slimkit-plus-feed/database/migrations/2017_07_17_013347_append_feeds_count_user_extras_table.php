<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendFeedsCountUserExtrasTable extends Migration
{
    /**
     * The migration table name.
     *
     * @var string
     */
    protected $table = 'user_extras';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn($this->table, 'feeds_count')) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->integer('feeds_count')->unsigned()->nullable()->default(0)->comment('用户分享统计');
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
        if (Schema::hasColumn($this->table, 'feeds_count')) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->dropColumn('feeds_count');
            });
        }
    }
}
