<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Packages\Installer\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;

class InstallController
{
    /**
     * Verify install password.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function verifyPassword(Request $request)
    {
        $password = $request->input('password');

        if (md5($password) !== config('installer.password')) {
            return response()->json(['message' => '安装密码错误！'], 422);
        }

        return response(null, 204);
    }

    /**
     * Get The LICENSE.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function license()
    {
        return response()->file(
            base_path('LICENSE')
        );
    }

    /**
     * Get check.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function check()
    {
        $messages = [];

        // PHP Version.
        $messages[] = version_compare(PHP_VERSION, '7.0.11', '>')
            ? [
            'type' => 'success',
            'message' => 'PHP 版本检测通过，当前运行版本为：'.PHP_VERSION,
        ]
            : [
            'type' => 'error',
            'message' => sprintf('你的 PHP 版本为：%s，运行程序至少需要：7.0.12', PHP_VERSION),
        ];

        $exts = ['dom', 'fileinfo', 'gd', 'json', 'mbstring', 'openssl', 'pdo'];
        foreach ($exts as $ext) {
            if (! extension_loaded($ext)) {
                $messages[] = [
                    'type' => 'error',
                    'message' => sprintf('PHP 拓展：%s 未安装', $ext),
                ];

                continue;
            }

            $messages[] = [
                'type' => 'success',
                'message' => sprintf('PHP 拓展：%s 已安装', $ext),
            ];
        }

        $paths = [
            'bootstrap',
            'config',
            'public',
            'storage',
        ];
        foreach ($paths as $path) {
            $path = base_path($path);
            if (is_writable($path)) {
                $messages[] = [
                    'type' => 'success',
                    'message' => sprintf('路径「%s」检查通过', $path),
                ];

                continue;
            }

            $messages[] = [
                'type' => 'error',
                'message' => sprintf('路径「%s」不可写', $path),
            ];
        }

        if (! config('app.key')) {
            $messages[] = [
                'type' => 'error',
                'message' => '应用密钥检查失败，请执行 php artisan key:generate 进行生成',
            ];
        }

        return response()->json($messages);
    }

    public function getInfo()
    {
        $data = [
            'name' => config('app.name'),
            'url' => config('app.url'),
            'databaseType' => config('database.default'),
        ];
        $data = array_merge($data, [
            'host' => config(sprintf('database.connections.%s.host', $data['databaseType'])),
            'port' => config(sprintf('database.connections.%s.port', $data['databaseType'])),
            'database' => config(sprintf('database.connections.%s.database', $data['databaseType'])),
            'username' => config(sprintf('database.connections.%s.username', $data['databaseType'])),
            'dbPassword' => config(sprintf('database.connections.%s.password', $data['databaseType'])),
        ]);

        return response()->json($data, 200);
    }

    public function setInfo(Request $request, Configuration $config)
    {
        $database = $request->only(['host', 'port', 'database', 'username']);
        $name = $request->input('name');
        $url = $request->input('url');
        $databaseType = $request->input('databaseType');
        $password = $request->input('dbPassword');
        $repo = $config->getConfiguration();
        $repo->set([
            'app.name' => $name,
            'app.url' => $url,
            'database.default' => $databaseType,
        ]);
        foreach ($database as $key => $value) {
            $repo->set(sprintf('database.connections.%s.%s', $databaseType, $key), $value);
        }
        $repo->set(sprintf('database.connections.%s.password', $databaseType), $password);

        $config->save($repo);

        return response('', 204);
    }
}
