<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Traits;

trait CreateJsonResponseData
{
    protected static function createJsonData(array $data = [])
    {
        $data = array_merge([
            'status'  => false,
            'code'    => 0,
            'message' => null,
            'data'    => null,
        ], $data);

        if (! $data['message']) {
            $data['message'] = $data['status'] === true ? '操作成功' : '操作失败';
        }

        return $data;
    }
}
