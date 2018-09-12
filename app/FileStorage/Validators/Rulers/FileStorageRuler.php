<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Validators\Rulers;

use Zhiyi\Plus\FileStorage\StorageInterface;

class FileStorageRuler implements RulersInterface
{
    /**
     * The storage.
     * @var \Zhiyi\Plus\FileStorage\StorageInterface
     */
    protected $storage;

    /**
     * Create the ruler instalce.
     * @param Zhiyi\Plus\FileStorage\StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage;
    }

    /**
     * Rule handler.
     * @param array $params
     * @return bool
     */
    public function handle(array $params): bool
    {
        try {
            return (bool) $this->storage
                ->meta(new Resource($params[1]))
                ->getSize();
        } finally {
            return false;
        }
    }
}
