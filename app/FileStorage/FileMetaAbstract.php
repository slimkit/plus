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
