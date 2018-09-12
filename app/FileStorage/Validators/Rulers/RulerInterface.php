<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Validators\Rulers;

interface RulerInterface
{
    /**
     * Rule handler.
     * @param array $params
     * @return bool
     */
    public function handle(array $params): bool;
}
