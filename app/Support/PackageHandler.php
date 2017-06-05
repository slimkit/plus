<?php

namespace Zhiyi\Plus\Support;

abstract class PackageHandler
{
    /**
     * The handles/.
     *
     * @var array
     */
    private static $handles = [];

    /**
     * The handler methods.
     *
     * @var array
     */
    protected $methods;

    /**
     * Get the handles.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function getHandles()
    {
        return static::$handles;
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param PackageHandler|string $handler
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function loadHandleFrom(string $name, $handler)
    {
        static::$handles[$name] = $handler;
    }

    /**
     * 转换处理方法名称为显示名称.
     *
     * @param string $handle
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function formatHandleToDisplay(string $handle): string
    {
        if (strtolower(substr($handle, -6)) === 'handle') {
            $handle = substr($handle, 0, -6);
        }

        return str_replace('_', '-', snake_case($handle));
    }

    /**
     * 转换处理方法为类方法名称.
     *
     * @param string $handle
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function formatHandleToMethod(string $handle): string
    {
        if (strtolower(substr($handle, -6)) === 'handle') {
            $handle = substr($handle, 0, -6);
        }

        return camel_case(str_replace('-', '_', $handle.'_handle'));
    }

    /**
     * Get the handler methods.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function methods(): array
    {
        if (! $this->methods) {
            $this->methods = [];
            foreach (get_class_methods($this) as $method) {
                if (strtolower(substr($method, -6)) === 'handle' && substr($method, -7) === substr($method, -7, 1).'Handle') {
                    array_push($this->methods, $this->formatHandleToDisplay($method));
                }
            }
        }

        return $this->methods;
    }

    /**
     *  Run handler.
     *
     * @param \Illuminate\Console\Command $command
     * @param string $handler
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($command, $handler)
    {
        $handler = $this->formatHandleToMethod($handler);
        if (! method_exists($this, $handler)) {
            throw new \RuntimeException('The handler not exist.');
        }

        return call_user_func_array([$this, $handler], [$command]);
    }
}
