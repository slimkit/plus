<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Zhiyi\Plus\Models\Ability;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run Abilitys Node Insert Data Method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        foreach ([
            'admin: login' => [
                'display_name' => '后台登录权限',
                'description' => '用户是否有权限登录后台',
            ],
            'admin:site:base' => [
                'display_name' => '系统-基本信息',
                'description' => '编辑系统配置基本信息权限',
            ],
            'admin:area:show' => [
                'display_name' => '系统-地区管理',
                'description' => '编辑系统地区管理权限',
            ],
            'admin:area:add' => [
                'display_name' => '地区管理-添加',
                'description' => '地区管理添加地区权限',
            ],
            'admin:storages' => [
                'display_name' => '储存管理',
                'description' => '编辑系统储存管理权限',
            ],
            'admin:area:update' => [
                'display_name' => '地区管理-更新地区',
                'description' => '地区管理修改权限',
            ],
            'admin:area:delete' => [
                'display_name' => '地区管理-删除',
                'description' => '地区管理删除地区权限',
            ],
            'admin:user:show' => [
                'display_name' => '用户管理',
                'description' => '用户管理查看权限',
            ],
            'admin:user:update' => [
                'display_name' => '用户管理-修改',
                'description' => '编辑用户信息权限',
            ],
            'admin:user:delete' => [
                'display_name' => '用户管理-删除',
                'description' => '删除用户权限',
            ],
            'admin:user:add' => [
                'display_name' => '用户管理-添加',
                'description' => '添加用户权限',
            ],
            'admin:role:show' => [
                'display_name' => '角色管理',
                'description' => '用户角色管理权限',
            ],
            'admin:role:update' => [
                'display_name' => '角色管理-编辑',
                'description' => '角色编辑权限',
            ],
            'admin: Deleting role' => [
                'display_name' => '角色管理-删除',
                'description' => '角色管理删除权限',
            ],
            'admin:role:add' => [
                'display_name' => '角色管理-添加',
                'description' => '角色添加权限',
            ],
            'admin:perm:show' => [
                'display_name' => '权限管理',
                'description' => '权限管理权限',
            ],
            'admin:perm:update' => [
                'display_name' => '权限管理-编辑',
                'description' => '权限管理编辑权限',
            ],
            'admin:perm:add' => [
                'display_name' => '权限管理-添加',
                'description' => '权限管理添加权限节点权限',
            ],
            'admin:perm:delete' => [
                'display_name' => '权限管理-删除',
                'description' => '权限管理删除权限节点权限',
            ],
            'admin:notice:send' => [
                'display_name' => '系统通知-发送',
                'description' => '系统通知发送系统通知权限',
            ],
            'admin: update feed topic' => [
                'display_name' => '动态»话题»编辑话题',
                'description' => '修改动态话题',
            ],
            'login' => [
                'display_name' => '登录',
                'description' => '用户登录权限',
            ],
            'password-update' => [
                'display_name' => '修改用户密码',
                'description' => '用户修改密码权限',
            ],
            'user-update' => [
                'display_name' => '修改用户资料',
                'description' => '用户修改资料权限',
            ],
            'user-follow' => [
                'display_name' => '关注用户',
                'description' => '用户关注权限',
            ],
            'storage-create' => [
                'display_name' => '上传附件',
                'description' => '用户上传附件权限',
            ],
            'feedback' => [
                'display_name' => '意见反馈',
                'description' => '用户意见反馈权限',
            ],
            'conversations' => [
                'display_name' => '系统会话',
                'description' => '用户获取系统会话权限',
            ],
            '[feed] Delete Feed' => [
                'display_name' => '[动态]->删除动态',
                'description' => '删除动态权限',
            ],
        ] as $name => $data) {
            Ability::firstOrCreate(['name' => $name], $data);
        }
    }
}
