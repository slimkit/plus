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

namespace Zhiyi\Plus\API2\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;

class Notification extends JsonResource
{
    use DateTimeToIso8601ZuluString;

    /**
     * The resource to array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->dateTimeToIso8601ZuluString($this->created_at),
            'read_at' => $this->dateTimeToIso8601ZuluString($this->read_at),
            'data' => $this->data,
        ];
    }
}
