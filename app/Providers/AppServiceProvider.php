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

namespace Zhiyi\Plus\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Observers\UserObserver;
use Zhiyi\Plus\Packages\Wallet\TargetTypeManager;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use function Zhiyi\Plus\validateChinaPhoneNumber;
use function Zhiyi\Plus\validateUsername;

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
        JsonResource::withoutWrapping();
        // 注册验证规则.
        $this->registerValidator();

        User::observe([
            UserObserver::class,
        ]);
    }

    /**
     * Resgister the application service.
     *
     * @return void
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
            $manager
                = $app->make(\Zhiyi\Plus\AtMessage\ResourceManagerInterface::class);

            return new \Zhiyi\Plus\AtMessage\Message($manager);
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
        $this->app->validator->extend('display_length',
            function ($attribute, string $value, array $parameters) {
                unset($attribute);

                return $this->validateDisplayLength($value, $parameters);
            });

        // 注册中英文显示宽度验证规则
        $this->app->validator->extend('display_width',
            function ($attribute, string $value, array $parameters) {
                unset($attribute);

                return $this->validateDisplayWidth($value, $parameters);
            });
    }

    /**
     * 验证显示长度计算.
     *
     * @param  string|int  $value
     * @param  array  $parameters
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateDisplayLength(string $value, array $parameters): bool
    {
        preg_match_all('/[a-zA-Z0-9_]/', $value, $single);
        $length = count($single[0]) / 2
            + mb_strlen(preg_replace('([a-zA-Z0-9_])', '', $value));

        return $this->validateBetween($length, $parameters);
    }

    /**
     * 验证中英文显示宽度.
     *
     * @param  string  $value
     * @param  array  $parameters
     *
     * @return bool
     */
    protected function validateDisplayWidth(string $value, array $parameters): bool
    {
        $number = strlen(mb_convert_encoding($value, 'GB18030', 'UTF-8'));

        return $this->validateBetween($number, $parameters);
    }

    /**
     * 验证一个数字是否在指定的最小最大值之间.
     *
     * @param  float  $number
     * @param  array  $parameters
     *
     * @return bool
     */
    private function validateBetween(float $number, array $parameters): bool
    {
        if (empty($parameters)) {
            throw new \InvalidArgumentException('Parameters must be passed');
        }

        [$min, $max] = array_pad($parameters, -2, 0);

        return $number >= $min && $number <= $max;
    }
}
