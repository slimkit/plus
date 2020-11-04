<?php

declare(strict_types=1);

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

namespace Slimkit\PlusAppversion\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zhiyi\Plus\Admin\Controllers\Controller;
use Zhiyi\Plus\FileStorage\StorageInterface;
use function Zhiyi\Plus\setting;

class DownloadController extends Controller
{
    public function __invoke(Request $request)
    {
        $qr = $request->file('qr');
        $bg = $request->file('bg');

        $this->storeFile('qr', $qr);
        $this->storeFile('bg', $bg);

        return new Response('', 204);
    }

    protected function storeFile(string $key, $file)
    {
        if (! $file) {
            return;
        }

        $storage = app()->make(StorageInterface::class);
        $resource = $storage->createResource('public', $storage->makePath($file->hashName()));
        $storage->put($resource, $file->get());

        setting('app-version')->set('download-'.$key, $storage->meta($resource)->url());
    }
}
