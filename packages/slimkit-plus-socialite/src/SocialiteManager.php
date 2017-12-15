<?php

namespace SlimKit\PlusSocialite;

use Illuminate\Support\Manager;
use SlimKit\PlusSocialite\Contracts\Sociable;

class SocialiteManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @throws \Exception
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getDefaultDriver()
    {
        throw new \Exception('不允许使用默认驱动，必须选择驱动并进行使用');
    }

    /**
     * Create Tencent QQ Driver.
     *
     * @return \SlimKit\PlusSocialite\Contracts\Sociable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createQQDriver(): Sociable
    {
        return $this->app->make(Drivers\QQDriver::class);
    }

    /**
     * Create Sina Weibo Driver.
     *
     * @return \SlimKit\PlusSocialite\Contracts\Sociable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createWeiboDriver(): Sociable
    {
        return $this->app->make(Drivers\WeiboDriver::class);
    }

    /**
     * Create Tencent WeChat Driver.
     *
     * @return \SlimKit\PlusSocialite\Contracts\Sociable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createWeChatDriver(): Sociable
    {
        return $this->app->make(Drivers\WeChatDriver::class);
    }
}
