<?php

declare(srrict_types=1);

namespace Zhiyi\Plus\FileStorage;

interface ResourceInterface
{
    /**
     * Get the resource channel.
     * @return string
     */
    public function getChannel(): string;

    /**
     * Get the resource path.
     * @return string
     */
    public function getPath(): string;

    /**
     * The resource to string.
     * @return string
     */
    public function __toString(): string;
}
