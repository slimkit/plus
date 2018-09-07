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

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use function Zhiyi\Plus\validateUsername;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use Illuminate\Http\Resources\Json\Resource;
use function Zhiyi\Plus\validateChinaPhoneNumber;
use Zhiyi\Plus\Packages\Wallet\TargetTypeManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Resource::withoutWrapping();
        // 注册验证规则.
        $this->registerValidator();
    }

    /**
     * Resgister the application service.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        $this->app->singleton('cdn', function ($app) {
            return new \Zhiyi\Plus\Cdn\UrlManager($app);
        });

        $this->app->singleton(TypeManager::class, function ($app) {
            return new TypeManager($app);
        });

        $this->app->singleton(TargetTypeManager::class, function ($app) {
            return new TargetTypeManager($app);
        });

        $this->app->singleton('at-message', function ($app) {
            $manager = $app->make(\Zhiyi\Plus\AtMessage\ResourceManagerInterface::class);
            $pusher = $app->make(\Zhiyi\Plus\Services\Push::class);
            $model = new \Zhiyi\Plus\Models\AtMessage();

            return new \Zhiyi\Plus\AtMessage\Message($manager, $model, $pusher);
        });

        $this->app->singleton('at-resource-manager', function ($app) {
            return new \Zhiyi\Plus\AtMessage\ResourceManager($app);
        });
    }

    /**
     * 注册验证规则.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerValidator()
    {
        // 注册中国大陆手机号码验证规则
        $this->app->validator->extend('cn_phone', function (...$parameters) {
            return validateChinaPhoneNumber($parameters[1]);
        });

        // 注册用户名验证规则
        $this->app->validator->extend('username', function (...$parameters) {
            return validateUsername($parameters[1]);
        });

        // 注册显示长度验证规则
        $this->app->validator->extend('display_length', function ($attribute, string $value, array $parameters) {
            unset($attribute);

            return $this->validateDisplayLength($value, $parameters);
        });
    }

    /**
     * 验证显示长度计算.
     *
     * @param strint|int $value
     * @param array $parameters
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateDisplayLength(string $value, array $parameters): bool
    {
        if (empty($parameters)) {
            throw new \InvalidArgumentException('Parameters must be passed');
        // 补充 min 位.
        } elseif (count($parameters) === 1) {
            $parameters = [0, array_first($parameters)];
        }

        list($min, $max) = $parameters;

        preg_match_all('/[a-zA-Z0-9_]/', $value, $single);
        $length = count($single[0]) / 2 + mb_strlen(preg_replace('([a-zA-Z0-9_])', '', $value));

        return $length >= $min && $length <= $max;
    }
}
