<?php

namespace Zhiyi\Plus\Unit\Middleware\User;

use Closure;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Http\Middleware\V1\ChangeUserCover;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChangeUserCoverTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test handle method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testHanle()
    {
        // Create ChangeUserCover::class mock.
        $changeUserCover = $this->getMockBuilder(ChangeUserCoverMiddlewareMock::class)
            ->setMethods(['storageTaskExiste'])
            ->getMock();
        $changeUserCover->method('storageTaskExiste')
            ->will($this->returnValue(true));

        // Create Request::class mock.
        $request = $this->createMock(Request::class);

        // test next.
        $changeUserCover->handle($request, Closure::bind(function () {
            $this->assertTrue(true);
        }, $this));

        // Test storageTaskExiste next.
        $request->expects($this->any())
            ->method('input')
            ->will($this->returnValue(true));

        $this->assertTrue(
            $changeUserCover->handle($request, function () {
            })
        );
    }

    /**
     * Test storageTaskExiste method.
     *
     * @author bs<414606094@qq.com>
     */
    public function testStorageTaskExiste()
    {
        $changeUserCover = $this->getMockBuilder(ChangeUserCoverMiddlewareMock::class)
            ->setMethods(['userProfileExiste'])
            ->getMock();

        $changeUserCover->method('userProfileExiste')
            ->will($this->returnValue(true));

        $storageTask = factory(StorageTask::class)->create();

        $request = $this->createMock(Request::class);
        $user = factory(User::class)->make();

        $request->expects($this->any())
            ->method('input')
            ->willReturn($storageTask->id);

        $request->expects($this->any())
            ->method('user')
            ->willReturn($user);

        $this->assertTrue(
            $changeUserCover->storageTaskExiste($storageTask->id, $request, function () {
            })
        );
    }
}

// mock class.
class ChangeUserCoverMiddlewareMock extends ChangeUserCover
{
    public function storageTaskExiste($storage_task_id, Request $request, Closure $next)
    {
        return parent::storageTaskExiste($storage_task_id, $request, $next);
    }

    public function userProfileExiste(User $user, StorageTask $task, Closure $next)
    {
        return parent::userProfileExiste($user, $task, $next);
    }
}
