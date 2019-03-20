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

class ImageDimension implements ImageDimensionInterface
{
    /**
     * The dimension width.
     * @var float
     */
    protected $width;

    /**
     * The dimnsion height.
     * @var float
     */
    protected $height;

    /**
     * Create a image dimension.
     * @param float $width
     * @param float $height
     */
    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Get image width (px).
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * Get image height (px).
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }
}
