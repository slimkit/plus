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

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Zhiyi\Plus\FileStorage\Pay\PayInterface;
use Zhiyi\Plus\Models\User;

interface FileMetaInterface extends Arrayable, JsonSerializable, Jsonable
{
    /**
     * Has the file is image.
     * @return bool
     */
    public function hasImage(): bool;

    /**
     * Get image file dimension.
     * @return \Zhiyi\Plus\FileStorage\ImageDimensionInterface
     */
    public function getImageDimension(): ImageDimensionInterface;

    /**
     * Get the file size (Byte).
     * @return int
     */
    public function getSize(): int;

    /**
     * Get the resource mime type.
     * @return string
     */
    public function getMimeType(): string;

    /**
     * Get the storage vendor name.
     * @return string
     */
    public function getVendorName(): string;

    /**
     * Get the resource pay info.
     * @param \Zhiyi\Plus\Models\User $user
     * @return \Zhiyi\Plus\FileStorage\Pay\PayInterface
     */
    public function getPay(User $user): ?PayInterface;

    /**
     * Get the resource url.
     * @return string
     */
    public function url(): string;
}
