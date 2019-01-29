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

namespace SlimKit\PlusSocialite\Drivers;

class WeiboDriver extends DriverAbstract
{
    /**
     * Get base URI.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getBaseURI(): string
    {
        return 'https://api.weibo.com/2';
    }

    /**
     * Get the provider.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function provider(): string
    {
        return 'weibo';
    }

    /**
     * Get union id for Weibo.
     *
     * @param string $accessToken
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unionid(string $accessToken): string
    {
        $resuilt = json_decode(
            $this->getHttpClient()->get('/account/get_uid.json', [
                'query' => ['access_token' => $accessToken],
                'headers' => ['Accept' => 'application/json'],
            ])
            ->getBody()
            ->getContents(), true
        );

        $this->abortIf(isset($resuilt['error']), function ($abort) use ($resuilt) {
            $abort(500, sprintf('%s (#%s)', $resuilt['error'], $resuilt['error_code']));
        });

        return (string) $resuilt['uid'];
    }
}
