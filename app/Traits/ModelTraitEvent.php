<?php

namespace Zhiyi\Plus\Traits;

trait ModelTraitEvent
{
    /**
     * 启动形状时间注册.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function bootModelTraitEvent()
    {
        $instance = new static;

        foreach ($instance->getObservableEvents() as $event) {
            static::registerModelEvent($event, function ($model) use ($event) {
                $model->runTraitsEvent($event, $model);
            });
        }
    }

    /**
     * 运行形状事件.
     *
     * @param string $event
     * @param self $model
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function runTraitsEvent(string $event, self $model)
    {
        $traits = class_uses_recursive(static::class);
        foreach ($traits as $trait) {
            $class = get_class($model);
            if (method_exists($class, $method = $event.class_basename($trait))) {
                if (forward_static_call_array([$class, $method], [$model]) === false) {
                    return false;
                }
            }
        }
    }
}
