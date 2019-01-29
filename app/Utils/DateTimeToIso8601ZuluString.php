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

namespace Zhiyi\Plus\Utils;

use Carbon\Carbon;

trait DateTimeToIso8601ZuluString
{
    /**
     * DateTime to ISO 8601 Zulu time.
     *
     * @param {\DateTime|string|null} $dateTime
     * @return string
     */
    protected function dateTimeToIso8601ZuluString($dateTime = null): ?string
    {
        if (is_null($dateTime) || empty($dateTime)) {
            return null;
        } elseif (! ($dateTime instanceof Carbon)) {
            $dateTime = new Carbon($dateTime);
        }

        return $dateTime->toIso8601ZuluString();
    }
}
