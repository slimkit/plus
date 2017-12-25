<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'music_special_links';

if (!Schema::hasColumn($component_table_name, 'special_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('special_id')->comment('专辑id');
    });
}

if (!Schema::hasColumn($component_table_name, 'music_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('music_id')->comment('歌曲id');
    });
}