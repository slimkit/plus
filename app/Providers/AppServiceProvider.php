<?php

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 添加验证手机号码规则
        Validator::extend('cn_phone', function ($attribute, $value) {
            return is_string($value) && \Zhiyi\Plus\is_cn_phone($value);
        });

        // 添加用户名验证规则
        Validator::extend('username', function ($attribute, $value) {
            return is_string($value) && \Zhiyi\Plus\is_username($value);
        });

        // 添加长度规则
        Validator::extend('display_length', function ($attribute, $value, array $parameters) {
            if (empty($parameters)) {
                throw new \InvalidArgumentException('Parameters must be passed');
            }

            $min = 0;
            if (count($parameters) === 1) {
                list($max) = $parameters;
            } elseif (count($parameters) >= 2) {
                list($min, $max) = $parameters;
            }

            if (! isset($max) || $max < $min) {
                throw new \InvalidArgumentException('The parameters passed are incorrect');
            }

            // 计算单字节.
            preg_match_all('/[a-zA-Z0-9_]/', $value, $single);
            $single = count($single[0]) / 2;

            // 多子节长度.
            $double = mb_strlen(preg_replace('([a-zA-Z0-9_])', '', $value));

            $length = $single + $double;

            return $length >= $min && $length <= $max;
        });

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
