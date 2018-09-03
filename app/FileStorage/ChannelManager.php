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

namespace Zhiyi\Plus\FileStorage;

use Illuminate\Http\Request;
use Zhiyi\Plus\AppInterface;
use Illuminate\Support\Manager;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\FileStorage\Channels\ChannelInterface;

class ChannelManager extends Manager
{
    /**
     * Support drives.
     * @var array
     */
    public const DRIVES = ['public', 'protected', 'private'];

    /**
     * Filesystem manager.
     * @var \Zhiyi\Plus\FileStorage\FilesystemManager
     */
    protected $fielsystemManager;

    /**
     * Create the manager instance.
     * @param \Zhiyi\Plus\AppInterface $app
     * @param \Zhiyi\Plus\FileStorage\FilesystemManager $fielsystemManager
     */
    public function __construct(AppInterface $app, FilesystemManager $fielsystemManager)
    {
        parent::__construct($app);
        $this->filesystemManager = $fielsystemManager;
    }

    /**
     * Get the default driver name.
     */
    public function getDefaultDriver()
    {
        return null;
    }

    /**
     * Create public channel driver.
     * @return \Zhiyi\Plus\FileStorage\Channels\ChannelInterface
     */
    protected function createPublicDriver(): ChannelInterface
    {
        $filesystem = $this->filesystemManager->driver(
            setting('core', 'file:public-channel-filesystem')
        );

        $channel = $this->app->make(Channels\PublicChannel::class);
        $channel->setFilesystem($filesystem);
        $channel->setRequest($this->app->make(Request::class));

        return $channel;
    }
}
