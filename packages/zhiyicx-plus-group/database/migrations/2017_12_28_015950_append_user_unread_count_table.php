<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendUserUnreadCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('user_unread_counts', 'unread_group_join_count')) {
            Schema::table('user_unread_counts', function (Blueprint $table) {
                $table->integer('unread_group_join_count')->unsigned()->default(0)->comment('未读待审核加圈统计');
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
        if (! Schema::hasColumn('user_unread_counts', 'unread_group_join_count')) {
            Schema::table('user_unread_counts', function (Blueprint $table) {
                $table->dropColumn('unread_group_join_count');
            });
        }
    }
}
