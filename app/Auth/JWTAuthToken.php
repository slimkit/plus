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

namespace Zhiyi\Plus\Auth;

use Tymon\JWTAuth\JWT;
use Zhiyi\Plus\Models\User as UserModel;

class JWTAuthToken
{
    /**
     * The \Tymon\JWTAuth\JWT instance.
     *
     * @var \Tymon\JWTAuth\JWT
     */
    protected $jwt;

    /**
     * Create the JWTAuthToken instance.
     *
     * @param \Tymon\JWTAuth\JWT $jwt
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Create user token.
     *
     * @param UserModel $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(UserModel $user)
    {
        return $this->jwt->fromUser($user);
    }

    /**
     * Refresh token.
     *
     * @param string $token
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function refresh(string $token)
    {
        $this->jwt->setToken($token);

        return $this->jwt->refresh();
    }
}
