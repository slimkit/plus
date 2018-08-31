<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Traits;

use Closure;

trait HasImageTrait
{
    abstract protected function useCustomTypes(): ?Closure;

    protected function getImageMIMETypes(): array
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

    protected function hasImageType(string $MIMEType): bool
    {
        return in_array($MIMEType, $this->getImageMIMETypes());
    }
}
