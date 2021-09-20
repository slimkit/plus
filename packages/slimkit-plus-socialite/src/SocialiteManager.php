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

namespace SlimKit\PlusSocialite;

use Illuminate\Support\Manager;
use SlimKit\PlusSocialite\Contracts\Sociable;

class SocialiteManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return void
     *
     * @throws \Exception
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getDefaultDriver(): void
    {
        throw new \RuntimeException('不允许使用默认驱动，必须选择驱动并进行使用');
    }

    /**
     * Create Tencent QQ Driver.
     *
     * @return Sociable
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createQQDriver(): Sociable
    {
        return $this->app->make(Drivers\QQDriver::class);
    }

    /**
     * Create Sina Weibo Driver.
     *
     * @return Sociable
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createWeiboDriver(): Sociable
    {
        return $this->app->make(Drivers\WeiboDriver::class);
    }

    /**
     * Create Tencent WeChat Driver.
     *
     * @return Sociable
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createWeChatDriver(): Sociable
    {
        return $this->app->make(Drivers\WeChatDriver::class);
    }
}
