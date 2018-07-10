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

namespace SlimKit\PlusSocialite\Traits;

use Closure;
use RuntimeException;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\User as UserModel;
use GuzzleHttp\Client as GuzzleHttpClient;
use SlimKit\PlusSocialite\Models\UserSocialite as UserSocialiteModel;

trait SocialiteDriverHelper
{
    /**
     *  Guzzle HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * Get Http client.
     *
     * @param bool $httpErrors
     * @return \GuzzleHttp\Client
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getHttpClient(bool $httpErrors = false): GuzzleHttpClient
    {
        if ($this->http instanceof GuzzleHttpClient) {
            return $this->http;
        }

        $baseURI = '';
        if (method_exists($this, 'getBaseURI')) {
            $baseURI = $this->getBaseURI();
        }

        return $this->http = new GuzzleHttpClient([
            'base_uri' => $baseURI,
            'http_errors' => $httpErrors,
        ]);
    }

    /**
     * Create User.
     *
     * @param string $name
     * @return \Zhiyi\Plus\Models\User
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createUserHelper(string $name, $role): UserModel
    {
        $user = new UserModel();
        $user->name = $name;
        $user->save();
        $user->roles()->sync($role);

        return $user;
    }

    /**
     * Get default user role.
     *
     * @return int|string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getDefaultUserRole()
    {
        $role = CommonConfig::byNamespace('user')
            ->byName('default_role')
            ->firstOr(function () {
                throw new RuntimeException('Failed to get the defined user group.');
            });

        return $role->value;
    }

    /**
     * Provider bind check abort.
     *
     * @param string $type
     * @param string $unionid
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function providerBindCheckAbort(string $type, string $unionid): bool
    {
        $this->abortIf(UserSocialiteModel::provider($type, $unionid)->first(), 422, '已绑定其他账号');

        return true;
    }

    /**
     * Provider Create user.
     *
     * @param string $type
     * @param string $unionid
     * @param string $name
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function providerStoreCreateUser(string $type, string $unionid, string $name)
    {
        $role = $this->getDefaultUserRole();
        $socialite = new UserSocialiteModel();
        $socialite->type = $type;
        $socialite->union_id = $unionid;

        return $socialite->getConnection()->transaction(function () use ($socialite, $role, $name) {

            // Create user.
            $user = $this->createUserHelper($name, $role);

            // Bind socialite.
            $socialite->user_id = $user->id;
            $socialite->save();

            return $this->createAuthToken($user);
        });
    }

    /**
     * Throw an HttpException with the given data.
     *
     * @param mixed $condition
     * @param int|\Closure $callable
     * @param string $message
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function abortIf($condition, $callable, string $message = '')
    {
        if (! $condition) {
            return;
        } elseif ($callable instanceof Closure) {
            $callable(function (int $code, string $message = '') {
                abort($code, $message);
            });
        }

        abort($callable, $message);
    }

    /**
     * 创建账户授权 Token.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createAuthToken(UserModel $user)
    {
        $jwtAuthToken = app(\Zhiyi\Plus\Auth\JWTAuthToken::class);
        if (! ($token = $jwtAuthToken->create($user))) {
            response()->json(['message' => ['Failed to create token.']], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => array_merge($user->toArray(), [
                'phone'  => $user->phone,
                'email'  => $user->email,
                'wallet' => $user->wallet,
                'initial_password' => $user->password === null ? false : true,
            ]),
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl'),
        ], 201);
    }
}
