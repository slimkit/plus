<?php

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

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The table name.
     * @var string
     */
    protected $table = 'settings';

    /**
     * Where by namespace scope.
     */
    public function scopeByNamespace($query, string $namespace)
    {
        return $query->where('namespace', $namespace);
    }

    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    public function setContentsAttribute($contents)
    {
        $this->attributes['contents'] = serialize($contents);

        return $this;
    }

    public function getContentsAttribute(string $contents)
    {
        return unserialize($contents);
    }
}
