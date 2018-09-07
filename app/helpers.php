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

namespace Zhiyi\Plus;

/**
 * 验证是否是中国验证码.
 *
 * @param string $number
 * @return bool
 */
function validateChinaPhoneNumber(string $number): bool
{
    return (bool) preg_match('/^(\+?0?86\-?)?1[3-9]\d{9}$/', $number);
}

/**
 * 验证用户名是否合法.
 *
 * @param string $username
 * @return bool
 */
function validateUsername(string $username): bool
{
    return (bool) preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $username);
}

/**
 * Get user login field.
 *
 * @param string $login
 * @param string $default
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 */
function username(string $login, string $default = 'id'): string
{
    $map = [
        'email' => filter_var($login, FILTER_VALIDATE_EMAIL),
        'phone' => validateChinaPhoneNumber($login),
        'name' => validateUsername($login),
    ];

    foreach ($map as $field => $value) {
        if ($value) {
            return $field;
        }
    }

    return $default;
}

/**
 * Find markdown image IDs.
 *
 * @param string $markdown
 * @return array
 * @author Seven Du <shiweidu@outlook.com>
 */
function findMarkdownImageIDs(string $markdown): array
{
    $pattern = '/\@\!\[.*?\]\((\d+?)\)/is';
    if (preg_match_all($pattern, $markdown, $matches) < 1) {
        return [];
    }

    return $matches[1];
}

/**
 * Filter URLs Return part of a string.
 *
 * @param string $data
 * @param int $length
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 */
function filterUrlStringLength(string $data, int $length = 0): string
{
    if (! $length) {
        return '';
    }

    // Match all URLs.
    preg_match_all('/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/', $data, $matches);
    $urls = $matches[0] ?? [];

    // Explode data.
    $data = preg_replace('/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/', '<a>', $data);
    $data = explode('<a>', $data);

    $value = '';
    $len = 0;
    foreach ($data as $key => $str) {
        if (($len + ($itemLength = mb_strlen($str))) <= $length) {
            $value .= $str;
            $value .= $urls[$key] ?? '';
            $len += $itemLength;

            continue;
        }

        $limit = $length - $len;
        $value .= mb_substr($str, 0, $limit);

        break;
    }

    return $value;
}

/**
 * Setting helper.
 *
 * @param string $namespace
 * @param string|null $name
 * @return any
 */
function setting(string $namespace, ?string $name = null, $default = null)
{
    $setting = \Zhiyi\Plus\Support\Setting::create($namespace);
    if ($name) {
        return $setting->get($name, $default);
    }

    return $setting;
}
