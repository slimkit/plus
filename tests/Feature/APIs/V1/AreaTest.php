<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AreaTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * test get area list.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetAreaList()
    {
        $response = $this->json('GET', '/api/v1/areas');

        $response->assertStatus(200);
    }
}
