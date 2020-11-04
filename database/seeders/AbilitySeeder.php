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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Ability;
use Zhiyi\Plus\Models\Role;

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
                'more' => [
                    'display_name' => '后台登录权限',
                    'description' => '用户是否有权限登录后台',
                ],
                'roles' => ['founder'],
            ],
            'admin:site:base' => [
                'more' => [
                    'display_name' => '系统-基本信息',
                    'description' => '编辑系统配置基本信息权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:area:show' => [
                'more' => [
                    'display_name' => '系统-地区管理',
                    'description' => '编辑系统地区管理权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:area:add' => [
                'more' => [
                    'display_name' => '地区管理-添加',
                    'description' => '地区管理添加地区权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:storages' => [
                'more' => [
                    'display_name' => '储存管理',
                    'description' => '编辑系统储存管理权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:area:update' => [
                'more' => [
                    'display_name' => '地区管理-更新地区',
                    'description' => '地区管理修改权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:area:delete' => [
                'more' => [
                    'display_name' => '地区管理-删除',
                    'description' => '地区管理删除地区权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:user:show' => [
                'more' => [
                    'display_name' => '用户管理',
                    'description' => '用户管理查看权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:user:update' => [
                'more' => [
                    'display_name' => '用户管理-修改',
                    'description' => '编辑用户信息权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:user:delete' => [
                'more' => [
                    'display_name' => '用户管理-删除',
                    'description' => '删除用户权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:user:add' => [
                'more' => [
                    'display_name' => '用户管理-添加',
                    'description' => '添加用户权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:role:show' => [
                'more' => [
                    'display_name' => '角色管理',
                    'description' => '用户角色管理权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:role:update' => [
                'more' => [
                    'display_name' => '角色管理-编辑',
                    'description' => '角色编辑权限',
                ],
                'roles' => ['founder'],
            ],
            'admin: Deleting role' => [
                'more' => [
                    'display_name' => '角色管理-删除',
                    'description' => '角色管理删除权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:role:add' => [
                'more' => [
                    'display_name' => '角色管理-添加',
                    'description' => '角色添加权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:perm:show' => [
                'more' => [
                    'display_name' => '权限管理',
                    'description' => '权限管理权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:perm:update' => [
                'more' => [
                    'display_name' => '权限管理-编辑',
                    'description' => '权限管理编辑权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:perm:add' => [
                'more' => [
                    'display_name' => '权限管理-添加',
                    'description' => '权限管理添加权限节点权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:perm:delete' => [
                'more' => [
                    'display_name' => '权限管理-删除',
                    'description' => '权限管理删除权限节点权限',
                ],
                'roles' => ['founder'],
            ],
            'admin:notice:send' => [
                'more' => [
                    'display_name' => '系统通知-发送',
                    'description' => '系统通知发送系统通知权限',
                ],
                'roles' => ['founder'],
            ],
            'admin: update feed topic' => [
                'more' => [
                    'display_name' => '动态»话题»编辑话题',
                    'description' => '修改动态话题',
                ],
                'roles' => ['founder'],
            ],
            'login' => [
                'more' => [
                    'display_name' => '登录',
                    'description' => '用户登录权限',
                ],
                'roles' => ['owner'],
            ],
            'password-update' => [
                'more' => [
                    'display_name' => '修改用户密码',
                    'description' => '用户修改密码权限',
                ],
                'roles' => ['owner'],
            ],
            'user-update' => [
                'more' => [
                    'display_name' => '修改用户资料',
                    'description' => '用户修改资料权限',
                ],
                'roles' => ['owner'],
            ],
            'user-follow' => [
                'more' => [
                    'display_name' => '关注用户',
                    'description' => '用户关注权限',
                ],
                'roles' => ['owner'],
            ],
            'storage-create' => [
                'more' => [
                    'display_name' => '上传附件',
                    'description' => '用户上传附件权限',
                ],
                'roles' => ['owner'],
            ],
            'feedback' => [
                'more' => [
                    'display_name' => '意见反馈',
                    'description' => '用户意见反馈权限',
                ],
                'roles' => ['owner'],
            ],
            'conversations' => [
                'more' => [
                    'display_name' => '系统会话',
                    'description' => '用户获取系统会话权限',
                ],
                'roles' => ['owner'],
            ],
            '[feed] Delete Feed' => [
                'more' => [
                    'display_name' => '[动态]->删除动态',
                    'description' => '删除动态权限',
                ],
                'roles' => ['founder'],
            ],
            '[News] Delete News Post' => [
                'more' => [
                    'display_name' => '[资讯] 删除资讯文章',
                    'description' => '删除资讯文章权限',
                ],
                'roles' => ['founder'],
            ],
        ] as $name => $data) {
            $ability = Ability::firstOrCreate(['name' => $name], $data['more']);
            foreach ($data['roles'] as $role) {
                $role = Role::where('name', 'like', $role)->first();
                if (! $role) {
                    continue;
                }

                $role->abilities()->sync($ability, false);
            }
        }
    }
}
