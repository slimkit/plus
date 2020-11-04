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

namespace Zhiyi\Plus\Support;

use Cache;
use Zhiyi\Plus\Models\Setting as Model;

class Setting
{
    /**
     * Storage database module.
     *
     * @var Model
     */
    protected $model;
    /**
     * Storage namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a setting namespace.
     *
     * @param  Model  $model
     * @param  string
     */
    public function __construct(Model $model, string $namespace)
    {
        $this->model = $model;
        $this->namespace = $namespace;
    }

    /**
     * Create a new setting namespace.
     *
     * @param  string|null  $namespace
     *
     * @return self
     */
    public function new(?string $namespace = null): self
    {
        return new static($this->model,
            $namespace ? $namespace : $this->namespace);
    }

    /**
     * Create new setting database builder.
     *
     * @return mixed
     */
    public function query()
    {
        return $this
            ->model
            ->query()
            ->byNamespace($this->namespace);
    }

    /**
     * Get namespace settings or name contents.
     *
     * @param  string|null  $name
     * @param  any  $default
     *
     * @return any
     */
    public function get(?string $name = null, $default = null)
    {
        if ($name) {
            return Cache::rememberForever(sprintf('setting_cache_%s', $name),
                function () use ($name, $default) {
                    $single = $this
                        ->query()
                        ->byName($name)
                        ->first();

                    return $single ? $single->contents : $default;
                });
        }

        return Cache::rememberForever(sprintf('setting_namespace_%s',
            $this->namespace), function () {
                $collection = $this->query()->get();

                return $collection->keyBy('name')->map(function ($value) {
                    return $value;
                });
            });
    }

    /**
     * Set contents to namespace.
     *
     * @param  array|string  $name
     * @param  any  $contents
     *
     * @return void
     * @throws \Throwable
     */
    public function set($name, $contents = null): void
    {
        if (is_array($name)) {
            $callbale = [$this, __METHOD__];
            $this->model->getConnection()->transaction(function () use (
                $name,
                $callbale
            ) {
                foreach ($name as $name => $contents) {
                    call_user_func($callbale, $name, $contents);
                }
                Cache::forget(sprintf('setting_namespace_%s',
                    $this->namespace));
            });

            return;
        }

        $setting = $this->query()->byName($name)->first();
        if (! $setting) {
            $setting = clone $this->model;
            $setting->namespace = $this->namespace;
            $setting->name = $name;
        }

        $setting->contents = $contents;
        $setting->save();
        Cache::forget(sprintf('setting_cache_%s', $name));
    }

    /**
     * The static method create a setting namespace.
     *
     * @param  string  $namespace
     *
     * @return self
     */
    public static function create(string $namespace)
    {
        return new static(new Model, $namespace);
    }
}
