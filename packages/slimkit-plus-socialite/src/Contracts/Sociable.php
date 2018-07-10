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

namespace SlimKit\PlusSocialite\Contracts;

use Zhiyi\Plus\Models\User as UserModel;

interface Sociable
{
    /**
     * Check bind and get user auth token.
     *
     * @param string $accessToken
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authUser(string $accessToken);

    /**
     * Bind provider for user.
     *
     * @param string $accessToken
     * @param Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function bindForUser(string $accessToken, UserModel $user);

    /**
     * Bind provider for account.
     *
     * @param string $accessToken
     * @param string|int $login
     * @param string $password
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function bindForAccount(string $accessToken, $login, string $password);

    /**
     * Create user and check create attribute.
     *
     * @param string $accessToken
     * @param string $name
     * @param bool $check
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createUser(string $accessToken, string $name, $check = false);

    /**
     * Unbind provider for user.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unbindForUser(UserModel $user);
}
