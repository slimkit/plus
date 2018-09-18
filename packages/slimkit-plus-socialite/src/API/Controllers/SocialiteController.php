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

namespace SlimKit\PlusSocialite\API\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusSocialite\SocialiteManager;
use SlimKit\PlusSocialite\Contracts\Sociable;
use SlimKit\PlusSocialite\API\Requests\CreateUserRequest;
use SlimKit\PlusSocialite\API\Requests\AccessTokenRequest;
use SlimKit\PlusSocialite\Models\UserSocialite as UserSocialiteModel;

class SocialiteController extends Controller
{
    /**
     * Socialite manager instance.
     *
     * @var \SlimKit\PlusSocialite\SocialiteManager
     */
    protected $socialite;

    /**
     * Provider maps.
     *
     * @var array
     */
    protected $providerMap = [
        'qq' => 'QQ',
        'weibo' => 'Weibo',
        'wechat' => 'WeChat',
    ];

    /**
     * Create socialite controler.
     *
     * @param \SlimKit\PlusSocialite\SocialiteManager $socialite
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(SocialiteManager $socialite)
    {
        $this->socialite = $socialite;
    }

    /**
     * Get all providers bind status.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function providersStatus(Request $request)
    {
        $user = $request->user();
        $providers = UserSocialiteModel::where('user_id', $user->id)->get();

        return response()->json($providers->pluck('type'), 200);
    }

    /**
     * Check bind and get user auth token.
     *
     * @param \SlimKit\PlusSocialite\API\Requests\AccessTokenRequest $request
     * @param string $provider
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function checkAuth(AccessTokenRequest $request, string $provider)
    {
        $accessToken = $request->input('access_token', '');
        $provider = $this->getProviderName($provider);

        return $this->provider($provider)->authUser($accessToken);
    }

    /**
     * Create user and return auth token.
     *
     * @param \SlimKit\PlusSocialite\API\Requests\CreateUserRequest $request
     * @param string $provider
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createUser(CreateUserRequest $request, string $provider)
    {
        $accessToken = $request->input('access_token', '');
        $name = $request->input('name', '');
        $check = $request->input('check', false);

        return $this->provider($provider)->createUser($accessToken, $name, $check);
    }

    /**
     * Bind provider for account.
     *
     * @param \SlimKit\PlusSocialite\API\Requests\AccessTokenRequest $request
     * @param string $provider
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function bindForAccount(AccessTokenRequest $request, string $provider)
    {
        $accessToken = $request->input('access_token');
        $login = (string) $request->input('login');
        $password = (string) $request->input('password');

        return $this->provider($provider)->bindForAccount($accessToken, $login, $password);
    }

    /**
     * Bind provider for user.
     *
     * @param \SlimKit\PlusSocialite\API\Requests\AccessTokenRequest $request
     * @param string $provider
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function bindForUser(AccessTokenRequest $request, string $provider)
    {
        $accessToken = $request->input('access_token');
        $user = $request->user();

        return $this->provider($provider)->bindForUser($accessToken, $user);
    }

    /**
     * Unbind provider for user.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $provider
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unbindForUser(Request $request, string $provider)
    {
        $user = $request->user();

        return $this->provider($provider)->unbindForUser($user);
    }

    /**
     * Get provider name.
     *
     * @param string $provider
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getProviderName(string $provider): string
    {
        return $this->providerMap[strtolower($provider)] ?? $provider;
    }

    /**
     * Get provider driver.
     *
     * @param string $provider
     * @return \SlimKit\PlusSoacialite\Contracts\Sociable
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function provider(string $provider): Sociable
    {
        return $this->socialite->driver(
            $this->getProviderName($provider)
        );
    }
}
