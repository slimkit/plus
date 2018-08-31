<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

class ImageDimension implements ImageDimensionInterface
{
    protected $width;
    protected $height;

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
