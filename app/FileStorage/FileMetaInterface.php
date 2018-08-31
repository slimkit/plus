<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

use \Zhiyi\Plus\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Zhiyi\Plus\FileStorage\Pay\PayInterface;

interface FileMetaInterface extends Arrayable
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

    public function url(): string;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array;
}
