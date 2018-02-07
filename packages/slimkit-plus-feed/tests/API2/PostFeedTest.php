<?php

declare(strict_types=1);

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Tests\API2;

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostFeedTest extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    private $user;

    /**
     * 前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
    }

    /**
     * @param $token
     * @return array
     */
    private function getFileId($token)
    {
        return $this->post('/api/v2/files?token='.$token, [
            'file' => UploadedFile::fake()->image('test.jpg')->size(200),
        ])->json();
    }

    public function testPostFeed()
    {
        $this->user->roles()->sync([2]);

        $token = $this->guard()->login($this->user);

        // 纯文本动态
        $this->contentfeed = $this->post($this->api.'?token='.$token, [
            'feed_content' => '测试动态',
            'feed_from' => 1,
            'feed_mark' => time().mt_rand(1000000, 9999999),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'amount' => 100,
            'images' => [],
        ]);

        // 图片动态
        $this->imagefeed = $this->post($this->api.'?token='.$token, [
            'feed_from' => 1,
            'feed_mark' => time().mt_rand(1000000, 9999999),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'images' => [
                ['id' => $this->getFileId($token)['id']],
            ],
        ]);

        $this->imageContentfeed = $this->post($this->api.'?token='.$token, [
            'feed_content' => '测试动态',
            'feed_from' => 1,
            'feed_mark' => time().mt_rand(1000000, 9999999),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'images' => [
                ['id' => $this->getFileId($token)['id']],
            ],
        ]);

        // 查看收费
        $this->amountReadfeed = $this->post($this->api.'?token='.$token, [
            'feed_content' => '测试动态',
            'feed_from' => 1,
            'feed_mark' => time().mt_rand(1000000, 9999999),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'images' => [
                ['id' => $this->getFileId($token)['id'], 'amount' => 100, 'type' => 'read'],
            ],
        ]);

        // 下载收费
        $this->amountDownloadfeed = $this->post($this->api.'?token='.$token, [
            'feed_content' => '测试动态',
            'feed_from' => 1,
            'feed_mark' => time().mt_rand(1000000, 9999999),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'images' => [
                ['id' => $this->getFileId($token)['id'], 'amount' => 100, 'type' => 'download'],
            ],
        ]);

        $this->contentfeed->assertStatus(201)->assertJsonStructure(['id', 'message']);
        $this->imagefeed->assertStatus(201)->assertJsonStructure(['id', 'message']);
        $this->imageContentfeed->assertStatus(201)->assertJsonStructure(['id', 'message']);
        $this->amountReadfeed->assertStatus(201)->assertJsonStructure(['id', 'message']);
        $this->amountDownloadfeed->assertStatus(201)->assertJsonStructure(['id', 'message']);
    }

    /**
     * @return Guard
     */
    protected function guard(): Guard
    {
        return Auth::guard('api');
    }
}
