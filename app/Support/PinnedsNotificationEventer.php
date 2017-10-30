<?php

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

        $notifications = $notifications->reject(function ($notification) {
            return ! class_exists($notification['namespace']) || array_diff_key($this->fillable, $notification);
        });

        return $notifications;
    }
}
