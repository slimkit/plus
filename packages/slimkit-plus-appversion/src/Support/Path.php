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

namespace Slimkit\PlusAppversion\Support;

class Path
{
    /**
     * relative PATH.
     *
     * @param string $fromPath
     * @param strong $toPath
     * @return string
     */
    public static function relative(string $fromPath, string $toPath): string
    {
        $fromPath = str_replace('\\', '/', realpath($fromPath));
        $toPath = str_replace('\\', '/', realpath($toPath));

        $fromParts = explode('/', $fromPath);
        $toParts = explode('/', $toPath);

        $length = min(count($fromParts), count($toParts));
        $samePartsLength = $length;
        for ($i = 0; $i < $length; $i++) {
            if ($fromParts[$i] !== $toParts[$i]) {
                $samePartsLength = $i;
                break;
            }
        }

        $outputParts = [];
        for ($i = $samePartsLength; $i < count($fromParts); $i++) {
            array_push($outputParts, '..');
        }

        $outputParts = array_merge($outputParts, array_slice($toParts, $samePartsLength) ?: []);

        return implode('/', $outputParts);
    }
}
