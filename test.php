<?php
/**
 * 将字符串进行函数处理.
 *
 * @author martinsun <syh@sunyonghong.com>
 * @datetime 2017-01-06T17:10:06+080
 *
 * @version  1.0
 *
 * @param [type] $name 处理函数
 * @param [type] $data 待处理数据
 *
 * @return array 处理结果数据
 */
function toHaddleString($name, ...$data) : array
{
    foreach ($data as $key => $value) {
        $data[$key] = $name($value);
    }

    return $data;
}
// $args = ['strtoupper', 'dnawun', 'daed', 'deada'];
// var_dump(toHaddleString(...$args));
