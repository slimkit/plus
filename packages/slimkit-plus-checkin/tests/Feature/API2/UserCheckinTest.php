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

namespace SlimKit\PlusCheckIn\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCheckinTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Set the test up.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setUp()
    {
        parent::setUp();

        config([
            'checkin.open' => true,
        ]);
    }

    /**
     * Test Not login request.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testNotLoginRequest()
    {
        $this
            ->json('GET', '/api/v2/user/checkin')
            ->assertStatus(401);
    }

    /**
     * Test get user checkin data.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetUserCheckin()
    {
        $user = factory(UserModel::class)->create();
        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/v2/user/checkin');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['rank_users', 'checked_in', 'checkin_count', 'last_checkin_count', 'attach_balance']);
    }
}
