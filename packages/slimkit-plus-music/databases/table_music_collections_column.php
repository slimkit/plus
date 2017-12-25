<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'music_collections';

if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('user_id')->comment('用户id');
    });
}

if (!Schema::hasColumn($component_table_name, 'special_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('special_id')->comment('专辑id');
    });
}
