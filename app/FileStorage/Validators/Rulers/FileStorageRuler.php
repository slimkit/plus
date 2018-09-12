<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\FileStorage\Validators\Rulers;

use Zhiyi\Plus\FileStorage\StorageInterface;

class FileStorageRuler implements RulerInterface
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
