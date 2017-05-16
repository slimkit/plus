<?php

namespace Zhiyi\Plus\Unit\Storages;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Storages\StorageTaskResponse;

class StorageTaskResponseTest extends TestCase
{
    /**
     * Test static create method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testStaticcCreate()
    {
        $this->assertInstanceOf(
            StorageTaskResponse::class,
            StorageTaskResponse::create('')
        );
    }

    /**
     * Test toArray method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testToArray()
    {
        $response = new StorageTaskResponse();

        $this->assertTrue(is_array($response->toArray()));
    }
}
