<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

use \Zhiyi\Plus\Models\User;
use Zhiyi\Plus\FileStorage\Pay\PayInterface;

interface FileMetaInterface
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
     * Get the resource pay info.
     * @param \Zhiyi\Plus\Models\User $user
     * @return \Zhiyi\Plus\FileStorage\Pay\PayInterface
     */
    public function getPay(User $user): ?PayInterface;
}
