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

namespace SlimKit\PlusCheckIn\Tests\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class UserCheckinTest extends TestCase
{
    use DatabaseTransactions;

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
        $user = UserModel::factory()->create();
        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/v2/user/checkin');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['rank_users', 'checked_in', 'checkin_count', 'last_checkin_count', 'attach_balance']);
    }
}
