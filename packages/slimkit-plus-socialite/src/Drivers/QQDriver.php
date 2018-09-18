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

class QQDriver extends DriverAbstract
{
    /**
     * Get base URI.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getBaseURI(): string
    {
        return 'https://graph.qq.com';
    }

    /**
     * Get the provider.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function provider(): string
    {
        return 'qq';
    }

    /**
     * Request Tencent QQ user unionid.
     *
     * @param string $accessToken
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unionid(string $accessToken): string
    {
        $resuilt = json_decode($this->removeCallback(
            $this->getHttpClient()->get('/oauth2.0/me', [
                'query' => [
                    'access_token' => $accessToken,
                    'unionid' => 1,
                ],
            ])
            ->getBody()
            ->getContents()
        ), true);

        $this->abortIf(isset($resuilt['error']), function ($abort) use ($resuilt) {
            $abort(500, sprintf('%s (#%s)', $resuilt['error_description'], $resuilt['error']));
        });

        return (string) $resuilt['unionid'];
    }

    /**
     * Remove the fucking callback parentheses.
     *
     * @param string $response
     *
     * @return string
     */
    protected function removeCallback(string $response): string
    {
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }

        return $response;
    }
}
