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

namespace SlimKit\PlusSocialite\Drivers;

use function Zhiyi\Plus\username;
use Zhiyi\Plus\Models\User as UserModel;
use SlimKit\PlusSocialite\Contracts\Sociable;
use SlimKit\PlusSocialite\Traits\SocialiteDriverHelper;
use SlimKit\PlusSocialite\Models\UserSocialite as UserSocialiteModel;

abstract class DriverAbstract implements Sociable
{
    use SocialiteDriverHelper;

    /**
     * Get provider type.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    abstract public function provider(): string;

    /**
     * Get provider union ID.
     *
     * @param string $accessToken
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    abstract public function unionid(string $accessToken): string;

    /**
     * Check bind and get user auth token.
     *
     * @param string $accessToken
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authUser(string $accessToken)
    {
        $unionid = $this->unionid($accessToken);
        $provider = $this->provider();
        $this->abortIf(! ($socialite = UserSocialiteModel::provider($provider, $unionid)->first()), function ($abort) {
            $abort(404, '请绑定账号');
        });

        return $this->createAuthToken($socialite->user);
    }

    /**
     * Bind provider for user.
     *
     * @param string $accessToken
     * @param Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function bindForUser(string $accessToken, UserModel $user)
    {
        if (! $user->phone) {
            return response()->json(['message' => ['绑定第三方账号必须绑定手机号码']], 422);
        }

        $provider = $this->provider();
        $this->abortIf(UserSocialiteModel::providerToUser($provider, $user->id)->first(),
            422, '你已绑定了其他第三方账号'
        );
        $unionid = $this->unionid($accessToken);
        $this->abortIf(UserSocialiteModel::provider($provider, $unionid)->first(), 422, '该第三方账号已于其他账号绑定');

        $socialite = new UserSocialiteModel();
        $socialite->user_id = $user->id;
        $socialite->union_id = $unionid;
        $socialite->type = $provider;
        $socialite->save();

        return response()->make('', 204);
    }

    /**
     * Bind provider for account.
     *
     * @param string $accessToken
     * @param string|int $login
     * @param string $password
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function bindForAccount(string $accessToken, $login, string $password)
    {
        $user = UserModel::where(username($login), $login)->first();
        $provider = $this->provider();

        if (! $user) {
            return response()->json(['login' => ['用户不存在']], 404);
        } elseif (! $user->verifyPassword($password)) {
            return response()->json(['password' => ['密码错误']], 422);
        } elseif (UserSocialiteModel::providerToUser($provider, $user->id)->first()) {
            return response()->json(['message' => ['该账户已绑定其他第三方账号']], 422);
        }

        $unionid = $this->unionid($accessToken);
        $this->abortIf(UserSocialiteModel::provider($provider, $unionid)->first(), 422, '该第三方账号已绑定其他账号');

        $socialite = new UserSocialiteModel();
        $socialite->user_id = $user->id;
        $socialite->union_id = $unionid;
        $socialite->type = $provider;
        $socialite->save();

        return $this->createAuthToken($user);
    }

    /**
     * Create user and check create attribute.
     *
     * @param string $accessToken
     * @param string $name
     * @param bool $check
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createUser(string $accessToken, string $name, $check = false)
    {
        $unionid = $this->unionid($accessToken);
        $provider = $this->provider();

        if ($this->providerBindCheckAbort($provider, $unionid) && $check) {
            return response()->make('', 204);
        }

        return $this->providerStoreCreateUser($provider, $unionid, $name);
    }

    /**
     * Unbind provider for user.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unbindForUser(UserModel $user)
    {
        $provider = $this->provider();

        if (! $user->phone) {
            return response()->json(['message' => ['解绑第三方账号必须绑定手机号码']], 422);
        }

        UserSocialiteModel::providerToUser($provider, $user->id)->delete();

        return response()->make('', 204);
    }
}
