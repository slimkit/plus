<?php

namespace Zhiyi\Plus;

/**
 * 验证是否是中国验证码.
 *
 * @param string $number
 * @return bool
 */
function validateChinaPhoneNumber(string $number): bool
{
    return preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[3678]|18\d)\d{8}|170[059]\d{7})$/', $number);
}

/**
 * 验证用户名是否合法.
 *
 * @param string $username
 * @return bool
 */
function validateUsername(string $username): bool
{
    return preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $username);
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
    $pattern = '/\@\!\[.*\]\((\d+)\)/is';
    if (preg_match_all($pattern, $markdown, $matches) < 1) {
        return [];
    }

    return $matches[1];
}
