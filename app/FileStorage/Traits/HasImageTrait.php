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

namespace Zhiyi\Plus\FileStorage\Traits;

use Closure;

trait HasImageTrait
{
    /**
     * Custom using MIME types.
     * @return null\Closure
     */
    abstract protected function useCustomTypes(): ?Closure;

    /**
     * Get support image MIME types.
     * @return array
     */
    protected function getImageMimeTypes(): array
    {
        if (! ($handler = $this->useCustomTypes())) {
            return [
                'application/x-photoshop',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'image/tiff',
                'image/webp',
            ];
        }

        return $handler();
    }

    /**
     * Check is support image type.
     * @param string $mimeTypes
     * @return bool
     */
    protected function hasImageType(string $mimeTypes): bool
    {
        return in_array($mimeTypes, $this->getImageMimeTypes());
    }
}
