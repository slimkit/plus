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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews;

use function asset as plus_asset;

/**
 * Generate an asset path for the application.
 *
 * @param string $path
 * @param bool $secure
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function asset($path, $secure = null)
{
    $path = asset_path($path);

    return plus_asset($path, $secure);
}

/**
 * Get The component resource asset path.
 *
 * @param string $path
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function asset_path($path)
{
    return component_name().'/'.$path;
}

/**
 * Get the component base path.
 *
 * @param string $path
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function base_path($path = '')
{
    return dirname(__DIR__).$path;
}

/**
 * Get the component name.
 *
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function component_name()
{
    return 'zhiyicx/plus-component-news';
}

/**
 * Get the component route filename.
 *
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function route_path()
{
    return base_path('/router.php');
}

/**
 * Get the component resource path.
 *
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function resource_path()
{
    return base_path('/resource');
}

function getShort($str, $length = 40, $ext = '')
{
    // $str = htmlspecialchars($str);
    $str = strip_tags($str);
    $str = htmlspecialchars_decode($str);
    $strlenth = 0;
    // $out = '';
    $output = '';
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
    foreach ($match[0] as $v) {
        preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $v, $matchs);
        if (! empty($matchs[0])) {
            $strlenth += 1;
        } elseif (is_numeric($v)) {
            //$strlenth +=  0.545;  // 字符像素宽度比例 汉字为1
            $strlenth += 0.5;    // 字符字节长度比例 汉字为1
        } else {
            //$strlenth +=  0.475;  // 字符像素宽度比例 汉字为1
            $strlenth += 0.5;    // 字符字节长度比例 汉字为1
        }

        if ($strlenth > $length) {
            $output .= $ext;
            break;
        }

        $output .= $v;
    }

    return $output;
}
