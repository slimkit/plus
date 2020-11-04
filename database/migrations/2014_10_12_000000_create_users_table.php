<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable()->default(null)->comment('user name.');
            $table->string('email', 150)->nullable()->default(null)->comment('user email.');
            $table->string('phone', 50)->nullable()->default(null)->comment('user phone member.');
            $table->string('password')->nullable()->default(null)->comment('password.');
            $table->string('bio')->nullable()->default(null)->comment('用户简介');
            $table->tinyInteger('sex')->nullable()->default(0)->comment('用户性别');
            $table->string('location')->nullable()->default(null)->comment('用户位置');
            $table->string('avatar')->nullable()->default(null)->comment('用户头像');
            $table->string('bg')->nullable()->default(null)->comment('个人主页背景');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken()->comment('user auth token.');
            $table->ipAddress('register_ip')->nullable()->default('::1')->comment('注册 ID');
            $table->ipAddress('last_login_ip')->nullable()->default('::1')->comment('最近登录 ID');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->unique('email');
            $table->unique('phone');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
