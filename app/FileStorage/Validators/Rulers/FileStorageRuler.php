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

namespace Zhiyi\Plus\FileStorage\Validators\Rulers;

use Exception;
use Zhiyi\Plus\FileStorage\Resource;
use Zhiyi\Plus\FileStorage\StorageInterface;

class FileStorageRuler implements RulerInterface
{
    /**
     * The storage.
     *
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Create the ruler instance.
     *
     * @param  StorageInterface  $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Rule handler.
     *
     * @param  array  $params
     *
     * @return bool
     */
    public function handle(array $params): bool
    {
        try {
            return (bool) $this->storage
                ->meta(new Resource($params[1]))
                ->getSize();
        } catch (Exception $e) {
            return false;
        }
    }
}
