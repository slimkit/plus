<?php

namespace SlimKit\PlusSocialite\Drivers;

class WeChatDriver extends DriverAbstract
{
    /**
     * Get base URI.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getBaseURI(): string
    {
        return 'https://api.weixin.qq.com';
    }

    /**
     * Get the provider.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function provider(): string
    {
        return 'wechat';
    }

    /**
     * Get WeChat union ID.
     *
     * @param string $accessToken
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unionid(string $accessToken): string
    {
        $resuilt = json_decode(
            $this->getHttpClient()->get('/sns/userinfo', [
                'headers' => ['Accept' => 'application/json'],
                'query' => [
                    'access_token' => $accessToken,
                    'openid' => md5($accessToken),
                ],
            ])
            ->getBody()
            ->getContents(), true
        );

        $this->abortIf(isset($resuilt['errcode']), function ($abort) use ($resuilt) {
            $abort(500, sprintf('%s (#%s)', $resuilt['errmsg'], $resuilt['errcode']));
        });

        return (string) $resuilt['unionid'];
    }
}
