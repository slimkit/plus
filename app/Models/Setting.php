<?php

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
