<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'music_diggs';

if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('user_id')->comment('用户id');
    });
}

if (!Schema::hasColumn($component_table_name, 'music_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('music_id')->comment('歌曲id');
    });
}
