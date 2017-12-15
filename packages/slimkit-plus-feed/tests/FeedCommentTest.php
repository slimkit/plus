<?php

namespace  Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\tests;

use Tests\TestCase;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class FeedCommentTest extends TestCase
{
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
     * Test the situation of adding comments normally.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testAddComment()
    {
        $uri = '/api/v1/feeds/'.$this->feed->id.'/comment';
        $responseHeader = ['ACCESS-TOKEN' => $this->auth->token];
        $responseBody = [
            'comment_content' => '这是一条garbage评论',
        ];
        $response = $this->postJson($uri, $responseBody, $responseHeader);
        $response->assertStatus(201);

        $json = [
            'status'  => true,
            'code'    => 0,
            'message' => '评论成功',
        ];
        $response->assertJson($json);
    }

    /**
     * Test the normal list of comments.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testGetCommentList()
    {
        $uri = '/api/v1/feeds/'.$this->feed->id.'/comments';
        $responseHeader = ['ACCESS-TOKEN' => $this->auth->token];
        $response = $this->getJson($uri, $responseHeader);
        $response->assertStatus(200);

        $json = [
            'status'  => true,
        ];
        $response->assertJson($json);
    }

    /**
     * 测试添加空评论.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testAddEmptyComment()
    {
        $uri = '/api/v1/feeds/'.$this->feed->id.'/comment';
        $responseHeader = ['ACCESS-TOKEN' => $this->auth->token];
        $responseBody = [
            'comment_content' => '',
        ];
        $response = $this->postJson($uri, $responseBody, $responseHeader);
        $response->assertStatus(400);

        $json = [
            'status' => false,
            'code' => 6007,
            'message' => '评论内容不能为空',
        ];
        $response->assertJson($json);
    }

    /**
     * 测试删除评论.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function testDelEmptyComment()
    {
        $uri = '/api/v1/feeds/9999999/comment/999999';
        $responseHeader = ['ACCESS-TOKEN' => $this->auth->token];
        $response = $this->delete($uri, [], $responseHeader);
        $response->assertStatus(204);
    }
}
