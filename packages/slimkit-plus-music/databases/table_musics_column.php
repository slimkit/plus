<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'musics';

if (!Schema::hasColumn($component_table_name, 'title')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('title')->comment('音乐标题');
    });
}

if (!Schema::hasColumn($component_table_name, 'singer')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('singer')->default(0)->comment('歌手id');
    });
}

if (!Schema::hasColumn($component_table_name, 'storage')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('storage')->comment('歌曲附件id');
    });
}

if (!Schema::hasColumn($component_table_name, 'last_time')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('last_time')->default(0)->comment('歌曲长度');
    });
}

if (!Schema::hasColumn($component_table_name, 'lyric')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->text('lyric')->nullable()->comment('歌词');
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

if(!Schema::hasColumn($component_table_name, 'sort')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('sort')->default(0)->comment('权重');
    });
}
