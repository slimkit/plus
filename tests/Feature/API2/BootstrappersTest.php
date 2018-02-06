<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;

class BootstrappersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetBootstrappers()
    {
        $response = $this->getJson('/api/v2/bootstrappers');

        $response->assertStatus(200);

        // $this->assertArrayHasKey('server:version', $response->json(), '版本号不存在');

        // $this->assertArrayHasKey('wallet:cash', $response->json(), '钱包提现开关不存在');

        // $this->assertArrayHasKey('wallet:recharge', $response->json(), '钱包充值开关不存在');

        // $this->assertArrayHasKey('currency:cash', $response->json(), '积分提现开关不存在');

        // $this->assertArrayHasKey('wallet:transform', $response->json(), '钱包转换积分开关不存在');

        // $this->assertArrayHasKey('currency:recharge', $response->json(), '积分充值相关选项不存在');

        // $this->assertArrayHasKey('open', $response->json()['currency:recharge'], '积分充值开关不存在');

        // $this->assertArrayHasKey('IAP_only', $response->json()['currency:recharge'], '苹果IAP充值开关不存在');

        // $this->assertArrayHasKey('ad', $response->json(), '启动广告不存在');

        // $this->assertArrayHasKey('site', $response->json(), '站点相关配置不存在');

        // $this->assertArrayHasKey('gold', $response->json()['site'], '积分开关不存在');

        // $this->assertArrayHasKey('status', $response->json()['site']['gold'], '积分开关不存在');

        // $this->assertArrayHasKey('reward', $response->json()['site'], '打赏开关不存在');

        // $this->assertArrayHasKey('status', $response->json()['site']['reward'], '打赏开关不存在');

        // $this->assertArrayHasKey('amounts', $response->json()['site']['reward'], '打赏金额配置不存在');

        // $this->assertArrayHasKey('reserved_nickname', $response->json()['site'], '保留昵称不存在');

        // $this->assertArrayHasKey('client_email', $response->json()['site'], '客户邮箱不存在');

        // $this->assertArrayHasKey('user_invite_template', $response->json()['site'], '用户邀请短信模板不存在');

        // $this->assertArrayHasKey('registerSettings', $response->json(), '注册设置不存在不存在');

        // $this->assertArrayHasKey('showTerms', $response->json()['registerSettings'], '服务条款设置不存在');

        // $this->assertArrayHasKey('method', $response->json()['registerSettings'], '注册方式设置不存在');

        // $this->assertArrayHasKey('content', $response->json()['registerSettings'], '服务条款内容不存在');

        // $this->assertArrayHasKey('type', $response->json()['registerSettings'], '不知道啥玩意不存在'); // 文档缺失该项
    }
}
