<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Pay;

interface PayInterface
{
    /**
     * Get paid status.
     * @return bool
     */
    public function getPaid(): bool;

    /**
     * Get file paid node amount.
     * @return int
     */
    public function getAmount(): int;

    /**
     * Get pay node.
     * @return string
     */
    public function getNode(): string;

    public function getType(): string;
}
