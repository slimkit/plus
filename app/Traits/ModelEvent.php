<?php

namespace Zhiyi\Plus\Traits;

trait ModelEvent
{
    protected static $modelEvents = [];

    /**
     * Register event.
     *
     * @param string $event
     * @param callable $callback
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function registerEvent(string $event,  callable $callback)
    {
        $class = static::class;
        if (! array_has(static::$modelEvents, $key = $class.'.'.$event)) {
            array_set($key, []);
        }

        static::$modelEvents[$class][$event][] = $callback;
    }

    /**
     * 启动注册.
     *
     * @return void|null|false
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function bootModelEvent()
    {
        $instance = new static;
        foreach ($instance->getObservableEvents() as $event) {
            static::registerModelEvent($event, function ($model) use ($event) {
                if ($model->runTraitsEvent($event, $model) === false || $model->dispatchEvent($event, $model) === false) {
                    return false;
                }
            });
        }
    }

    /**
     * Fire the event.
     *
     * @param string $event
     * @param self $model
     * @return void|null|false
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function dispatchEvent(string $event, self $model)
    {
        $class = get_class($model);
        $events = (array) array_get(static::$modelEvents, $class.'.'.$event, []);
        foreach ($events as $event) {
            if (call_user_func_array($event, [$model]) === false) {
                return false;
            }
        }
    }

    /**
     * 运行性状事件.
     *
     * @param string $event
     * @param self $model
     * @return void|null|false
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function runTraitsEvent(string $event, self $model)
    {
        $traits = class_uses_recursive($class = get_class($model));
        foreach ($traits as $trait) {
            if (method_exists($class, $method = $event.class_basename($trait))) {
                if (forward_static_call_array([$class, $method], [$model]) === false) {
                    return false;
                }
            }
        }
    }
}
