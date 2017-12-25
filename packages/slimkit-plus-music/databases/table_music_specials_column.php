<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'music_specials';

if (!Schema::hasColumn($component_table_name, 'title')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('title')->comment('专辑标题');
    });
}

if (!Schema::hasColumn($component_table_name, 'intro')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('intro')->nullable()->comment('简介');
    });
}

if (!Schema::hasColumn($component_table_name, 'storage')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('storage')->comment('封面id');
    });
}

if (!Schema::hasColumn($component_table_name, 'taste_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('taste_count')->default(0)->comment('播放数量');
    });
}

if (!Schema::hasColumn($component_table_name, 'share_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('share_count')->default(0)->comment('分享数量');
    });
}

if (!Schema::hasColumn($component_table_name, 'comment_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('comment_count')->default(0)->comment('评论数量');
    });
}

if (!Schema::hasColumn($component_table_name, 'collect_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('collect_count')->default(0)->comment('收藏数量');
    });
}

if(!Schema::hasColumn($component_table_name, 'sort')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('sort')->default(0)->comment('权重');
    });
}
