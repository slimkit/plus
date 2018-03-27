<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use function Zhiyi\Plus\validateUsername;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use function Zhiyi\Plus\validateChinaPhoneNumber;
use Zhiyi\Plus\Packages\Wallet\TargetTypeManager;
use Illuminate\Database\Eloquent\Relations\Relation;

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

        $this->registerMorpMap();
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

        $length = strlen(mb_convert_encoding($value, 'GB18030', 'UTF-8')) / 2;

        return $length >= $min && $length <= $max;
    }

    /**
     * Register model morp map.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerMorpMap()
    {
        $this->setMorphMap([
            'users' => \Zhiyi\Plus\Models\User::class,
            'comments' => \Zhiyi\Plus\Models\Comment::class,
        ]);
    }

    /**
     * Set the morph map for polymorphic relations.
     *
     * @param array|null $map
     * @param bool|bool $merge
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function setMorphMap(array $map = null, bool $merge = true)
    {
        Relation::morphMap($map, $merge);
    }
}
