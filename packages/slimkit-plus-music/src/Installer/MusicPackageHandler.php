<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Installer;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Support\PackageHandler;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;

class MusicPackageHandler extends PackageHandler
{
    public function defaultHandle($command)
    {
        $handle = $command->choice('Select handle', ['list', 'install', 'remove', 'checkstorage', 'quit'], 0);

        if ($handle !== 'quit') {
            return $command->call(
                $command->getName(),
                array_merge($command->argument(), ['handle' => $handle])
            );
        }
    }

    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for music')) {
            Comment::whereIn('commentable_type', ['musics', 'music_specials'])->delete();

            return $command->info('The Music has been removed');
        }
    }

    public function installHandle($command)
    {
        return $command->info('Music component install successfully');
    }
}
