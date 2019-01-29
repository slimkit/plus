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

use Closure;
use Illuminate\Contracts\Events\Dispatcher;

class PinnedsNotificationEventer
{
    protected $events;

    // 默认填充字段格式
    protected $fillable = [
        'name' => '',
        'namespace' => '',
        'owner_prefix' => '',
        'wherecolumn' => '',
    ];

    protected $prefix = 'pinneds_notifications';

    /**
     * create eventer instance.
     *
     * @param Dispatcher $events
     * @author BS <414606094@qq.com>
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * register a listener.
     *
     * @param Closure $callback [description]
     * @return [type] [description]
     * @author BS <414606094@qq.com>
     */
    public function listen(Closure $callback)
    {
        return $this->events->listen($this->prefix, $callback);
    }

    /**
     * call the listeners.
     *
     * @return [type] [description]
     * @author BS <414606094@qq.com>
     */
    public function dispatch()
    {
        $notifications = collect($this->events->dispatch($this->prefix));

        return $notifications->reject(function ($notification): bool {
            if (! is_array($notification)) {
                return true;
            } elseif (! isset($notification['namespace'])) {
                return true;
            } elseif (! class_exists($notification['namespace'])) {
                return true;
            } elseif (array_diff_key($this->fillable, $notification)) {
                return true;
            }

            return false;
        });
    }
}
