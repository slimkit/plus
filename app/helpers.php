<?php

namespace Zhiyi\Plus;

/**
 * 验证
 *
 * @param string $value
 * @return bool
 */
function validateChinaPhoneNumber(string $value): bool
{
    return preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[3678]|18\d)\d{8}|170[059]\d{7})$/', $value);
}

if (! function_exists(__NAMESPACE__.'\is_username')) {

    /**
     * 检查一个字符串是否一个用户名格式.
     *
     * @param string $value
     * @return bool
     */
    function is_username(string $value): bool
    {
        return preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $value);
    }
}
