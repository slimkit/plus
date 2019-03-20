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

class ManageRepository
{
    protected static $manages = [];

    /**
     * Push manage url.
     *
     * @param string $name
     * @param string $uri
     * @param array $option
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function loadManageFrom(string $name, string $uri, array $option = [])
    {
        static::$manages[] = [
            'name' => $name,
            'uri' => $uri,
            'option' => $option,
        ];
    }

    /**
     * Get the manages for the provider.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getManages(): array
    {
        $manages = [];
        foreach (static::$manages as $item) {
            $name = $item['name'];
            $uri = $item['uri'];
            $option = $item['option'];

            $isRoute = $option['route'] ?? false;
            $parameters = (array) ($option['parameters'] ?? []);
            $absolute = $option['absolute'] ?? true;
            $icon = $option['icon'] ?? null;

            $manages[] = [
                'name' => $name,
                'icon' => $icon,
                'uri' => ! $isRoute ? $uri : route($uri, $parameters, $absolute),
            ];
        }

        return $manages;
    }
}
