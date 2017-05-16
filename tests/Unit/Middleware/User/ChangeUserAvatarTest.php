<?php

namespace Zhiyi\Plus\Unit\Middleware\User;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Models\UserProfileSetting;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\V1\ChangeUserAvatar;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChangeUserAvatarTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test middleware with empty task.
     *
     * @author bs<414606094@qq.com>
     */
    public function testEmptyTask()
    {
        $storageTask = factory(StorageTask::class)->create();
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input'])
            ->getMock();

        $map = [
            ['storage_task_id', null, $storageTask->id],
        ];
        $request->expects($this->any())
            ->method('input')
            ->will($this->returnValueMap($map));
        $storageTask->delete();

        $response = TestResponse::fromBaseResponse(
            with(new ChangeUserAvatar())->handle($request, function () {
            })
        );

        $response->assertStatus(403);
    }

    /**
     * test change avatar without avatar profile.
     *
     * @author bs<414606094@qq.com>
     */
    public function testEmptyProfile()
    {
        $storageTask = factory(StorageTask::class)->create();
        $user = factory(User::class)->create();
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input', 'user'])
            ->getMock();

        $map = [
            ['storage_task_id', null, $storageTask->id],
        ];
        $request->expects($this->any())
            ->method('input')
            ->will($this->returnValueMap($map));
        $request->expects($this->any())
            ->method('user')
            ->willReturn($user);

        UserProfileSetting::where('profile', 'avatar')->delete();

        $response = TestResponse::fromBaseResponse(
            with(new ChangeUserAvatar())->handle($request, function () {
            })
        );

        $response->assertStatus(500);
    }

    /**
     * test change avatar without storage.
     *
     * @author bs<414606094@qq.com>
     */
    public function testEmptyStorage()
    {
        $storageTask = factory(StorageTask::class)->create();
        $user = factory(User::class)->create();
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input', 'user'])
            ->getMock();

        $map = [
            ['storage_task_id', null, $storageTask->id],
        ];
        $request->expects($this->any())
            ->method('input')
            ->will($this->returnValueMap($map));
        $request->expects($this->any())
            ->method('user')
            ->willReturn($user);

        $response = TestResponse::fromBaseResponse(
            with(new ChangeUserAvatar())->handle($request, function () {
            })
        );

        $response->assertStatus(404);
    }
}
