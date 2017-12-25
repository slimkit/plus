<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'music_singers';

if (!Schema::hasColumn($component_table_name, 'name')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('name')->comment('歌手名称');
    });
}

if (!Schema::hasColumn($component_table_name, 'cover')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cover')->comment('歌手封面id');
    });
}
