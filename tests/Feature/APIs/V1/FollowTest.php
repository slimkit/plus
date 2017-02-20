<?php

namespace Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\User;

class FollowTest extends TestCase
{
    protected $followingUri = '/api/v1/users/follow';
    protected $followedUri = '/api/v1/users/unFollow';
    protected $followingListUri = '/api/v1/follows/follows';
    protected $followedListUri = '/api/v1/follows/followeds';
    protected $user;
    protected $followedUser;
    protected $auth;

    /**
     * 注入数据.
     */
    protected function setUp()
    {
        parent::setUp();

        // register user.
        $this->user = User::create([
            'phone'    => '1878'.rand(1111111, 9999999),
            'name'     => 'ts'.rand(1000, 9999),
            'password' => bcrypt(123456),
        ]);

        $this->followedUser = User::create([
            'phone'    => '1890'.rand(1111111, 9999999),
            'name'     => 'ts'.rand(1000, 9999),
            'password' => bcrypt(123456),
        ]);

        // set token info.
        $this->auth = new AuthToken();
        $this->auth->token = md5(str_random(32));
        $this->auth->refresh_token = md5(str_random(32));
        $this->auth->expires = 0;
        $this->auth->state = 1;

        // save token.
        $this->user->tokens()->save($this->auth);
    }

    /**
     * 卸载方法，清理测试后的冗余数据.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function tearDown()
    {
        $this->user->forceDelete();
        $this->followedUser->forceDelete();
        $this->auth->forceDelete();
        parent::tearDown();
    }

    /**
     * 关注时目标用户不存在.
     *
     * @return [type] [description]
     */
    public function testNotFoundFollowingUser()
    {
        $uri = $this->followingUri;
        $response = $this->post($uri, [
            'user_id' => 555,
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(404);

        $json = static::createJsonData([
            'status'  => false,
            'code'    => 1019,
            'message' => '目标用户不存在',
            'data'    => null,

        ]);
        $response->assertJson($json);
    }

    /**
     * 取关时目标用户不存在.
     *
     * @return [type] [description]
     */
    public function testNotFoundFollowedUser()
    {
        $uri = $this->followedUri;
        $response = $this->delete($uri, [
            'user_id' => 555,
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(404);

        $json = static::createJsonData([
            'status'  => false,
            'code'    => 1019,
            'message' => '目标用户不存在',
            'data'    => null,

        ]);
        $response->assertJson($json);
    }

    /**
     * 关注用户成功
     *
     * @return [type] [description]
     */
    public function testSuccessFollow()
    {
        $uri = $this->followingUri;
        $response = $this->post($uri, [
            'user_id' => $this->followedUser->id,
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(201);

        $json = static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '关注成功',
            'data'    => null,

        ]);
        $response->assertJson($json);
    }

    /**
     * 已经关注用户的情况下再次关注用户.
     *
     * @return [type] [description]
     */
    public function testHasFollowFollowingUser()
    {
        $uri = $this->followingUri;

        $follow = new Following();
        $followed = new Followed();
        $follow->user_id = $this->user->id;
        $follow->following_user_id = $this->followedUser->id;
        $follow->save();

        $followed->user_id = $this->followedUser->id;
        $followed->followed_user_id = $this->user->id;
        $followed->save();

        $response = $this->post($uri, [
            'user_id' => $this->followedUser->id,
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(400);

        $json = static::createJsonData([
            'status'  => false,
            'code'    => 1020,
            'message' => '您已经关注了此用户',
            'data'    => null,

        ]);
        $response->assertJson($json);
    }

    /**
     * 取消关注.
     *
     * @return [type] [description]
     */
    public function testUnFollowUser()
    {
        $uri = $this->followedUri;

        $follow = new Following();
        $followed = new Followed();
        $follow->user_id = $this->user->id;
        $follow->following_user_id = $this->followedUser->id;
        $follow->save();

        $followed->user_id = $this->followedUser->id;
        $followed->followed_user_id = $this->user->id;
        $followed->save();

        $response = $this->delete($uri, [
            'user_id' => $this->followedUser->id,
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(200);

        $json = static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '成功取关',
            'data'    => null,

        ]);
        $response->assertJson($json);
    }

    /**
     * 没有关注用户的情况下取关.
     *
     * @return [type] [description]
     */
    public function testNotFollowUser()
    {
        $uri = $this->followedUri;
        $response = $this->delete($uri, [
            'user_id' => $this->followedUser->id,
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(400);

        $json = static::createJsonData([
            'status'  => false,
            'code'    => 1021,
            'message' => '您并没有关注此用户',
            'data'    => null,

        ]);
        $response->assertJson($json);
    }
}
