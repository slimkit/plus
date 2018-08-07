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

namespace Zhiyi\Plus\Support;

use Zhiyi\Plus\Models\Setting as Model;

class Setting
{
    /**
     * Storage database module.
     * @var \Zhiyi\Plus\Models\Setting
     */
    protected $model;

    /**
     * Storage namespace.
     * @var string
     */
    protected $namespace;

    /**
     * Create a setting namespace.
     * @param \Zhiyi\Plus\Models\Setting
     * @param string
     */
    public function __construct(Model $model, string $namespace)
    {
        $this->model = $model;
        $this->namespace = $namespace;
    }

    /**
     * Create a new setting namespace.
     * @param string|null $namespace
     * @return self
     */
    public function new(?string $namespace = null): self
    {
        return new static($this->model, $namespace ? $namespace : $this->namespace);
    }

    /**
     * Create new setting database builder.
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
     * @param string|null $name
     * @param any $default
     * @return any
     */
    public function get(?string $name = null, $default = null)
    {
        if ($name) {
            $single = $this
                ->query()
                ->byName($name)
                ->first();

            return $single ? $single->contents : $default;
        }

        $collection = $this->query()->get();

        return $collection->keyBy('name')->map(function ($value) {
            return $value;
        });
    }

    /**
     * Set contents to namespace.
     * @param array|string $name
     * @param any $contents
     * @return void
     */
    public function set($name, $contents = null): void
    {
        if (is_array($name)) {
            $callbale = [$this, __METHOD__];
            $this->module->getConnection()->transaction(function () use ($name, $callbale) {
                foreach ($name as $name => $contents) {
                    call_user_func($callbale, $name, $contents);
                }
            });
        }

        $setting = $this->query()->byName($name)->first();
        if (! $setting) {
            $setting = clone $this->model;
            $setting->namespace = $this->namespace;
            $setting->name = $name;
        }

        $setting->contents = $contents;
        $setting->save();
    }

    /**
     * The static method create a setting namespace.
     * @param string $namespace
     * @return self
     */
    public static function create(string $namespace)
    {
        return new static(new Model, $namespace);
    }
}
