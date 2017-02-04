<?php

namespace Zhiyi\Plus;

if (!function_exists('memory_storage')) {
    /**
     * 内存储存.
     *
     * @param string|int $key
     * @param mixed      $value
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    function memory_storage($key = null, $value = null)
    {
        static $storage = [];

        if ($key === null) {
            return $storage;
        } elseif ($value === null) {
            return array_get($storage, $key);
        }

        array_set($storage, $key, $value);
    }
}

if (!function_exists('push_router')) {
    /**
     * 添加路由注入.
     *
     * @param string $namespace  Controller所在命名空间
     * @param string $routerFile 路由配置文件地址
     * @param array  $options    更多设置，参考Route::group
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    function push_router(string $namespace, string $routerFile, array $options = [])
    {
        $storage_key = 'http_routers';
        $options['namespace'] = $namespace;

        $routers = memory_storage($storage_key) ?: [];
        $routes[$routerFile] = $options;

        memory_storage($storage_key, $routers);
    }
}

if (!function_exists('routes_all')) {
    /**
     * 获取全部push的路由.
     *
     * @return array 路由数组
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    function routes_all(): array
    {
        return memory_storage('http_routers') ?: [];
    }
}
