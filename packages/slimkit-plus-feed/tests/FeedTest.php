<?php

namespace  Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\tests;

use Tests\TestCase;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class FeedTest extends TestCase
{
    protected $uri = '/api/v1/feeds';

    protected $user;
    protected $auth;

    protected function setUp()
    {
        parent::setUp();

        // register user.
        $this->user = User::create([
            'phone'    => '1878'.rand(1111111, 9999999),
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

        $this->feed = new Feed();
        $this->feed->feed_content = '这是一条garbage数据';
        $this->feed->feed_client_id = '127.0.0.1';
        $this->feed->user_id = $this->user->id;
        $this->feed->feed_from = 4;
        $this->feed->feed_latitude = '';
        $this->feed->feed_longtitude = '';
        $this->feed->feed_geohash = '';
        $this->feed->feed_title = '123';
        $this->feed->save();
    }

    /**
     * Clean up the test after the redundant data.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function tearDown()
    {
        $this->feed->forceDelete();
        $this->user->forceDelete();
        $this->auth->forceDelete();
        parent::tearDown();
    }

    /**
     * 测试正常获取动态列表.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testGetFeedList()
    {
        $response = $this->getJson($this->uri);
        $response->assertStatus(200);

        $json = [
            'status'  => true,
            'code'    => 0,
            'message' => '动态列表获取成功',
        ];
        $response->assertJson($json);
        unset($response);
    }

    /**
     * 测试正常获取单条数据.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testGetFeedInfo()
    {
        $uri = $this->uri.'/'.$this->feed->id;
        $response = $this->get($uri);
        $response->assertStatus(200);
        $json = [
            'status'  => true,
            'code'    => 0,
            'message' => '获取动态成功',
        ];

        $response->assertJson($json);
        $feed->delete();
    }

    /**
     * Test incoming error id gets feed.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testGetFeedInfoWithErrorId()
    {
        $uri = $this->uri.'/'.rand(1111111, 9999999);
        $response = $this->getJson($uri);
        $response->assertStatus(404);

        $json = [
            'status'  => false,
            'code'    => 6004,
            'message' => '动态不存在或已被删除',
        ];
        $response->assertJson($json);
    }

    /**
     * Test to send the feed situation.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testPostFeed()
    {
        $responseHeader = ['ACCESS-TOKEN' => $this->auth->token];
        $responseBody = [
            'feed_content'      => '这是一条garbage数据',
            'feed_client_id'    => '127.0.0.1',
            'user_id'           => $this->user->id,
            'feed_from'         => 4,
            'feed_latitude'     => '',
            'feed_longtitude'   => '',
            'feed_geohash'      => '',
            'feed_title'        => '123',
        ];

        $response = $this->postJson($this->uri, $responseBody, $responseHeader);
        $response->assertStatus(201);

        $json = [
            'status'  => true,
            'code'    => 0,
            'message' => '动态创建成功',
        ];
        $response->assertJson($json);
    }

    /**
     * The test sends feed that does not contain content.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testPostFeedWithNoContent()
    {
        $responseHeader = ['ACCESS-TOKEN' => $this->auth->token];
        $responseBody = [
            'feed_content'      => '',
            'feed_client_id'    => '127.0.0.1',
            'user_id'           => $this->user->id,
            'feed_from'         => 4,
            'feed_latitude'     => '',
            'feed_longtitude'   => '',
            'feed_geohash'      => '',
            'feed_title'        => '123',
        ];

        $response = $this->postJson($this->uri, $responseBody, $responseHeader);
        $response->assertStatus(400);
        $json = [
            'status' => false,
            'code' => 6001,
            'message' => '动态内容不能为空',
        ];
        $response->assertJson($json);
    }
}
