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

namespace Zhiyi\Plus\FileStorage;

abstract class FileMetaAbstract implements FileMetaInterface
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $baseArr = [
            'url' => $this->url(),
            'vendor' => $this->getVendorName(),
            'mime' => $this->getMimeType(),
            'size' => $this->getSize(),
        ];
        if ($this->hasImage()) {
            $baseArr['dimension'] = [
                'width' => $this->getImageDimension()->getWidth(),
                'height' => $this->getImageDimension()->getHeight(),
            ];
        }

        return $baseArr;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the object to its JSON representation.
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->toJson();
    }
}
