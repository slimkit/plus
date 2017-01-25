<?php

namespace Tests\Feature\APIs\V1;

use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * 创建MessageResponseBody对象数据体.
     *
     * @param array $body 数据
     *
     * @return array 创建的数据
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function createMessageResponseBody(array $body): array
    {
        return app(MessageResponseBody::class, $body)->getBody();
    }
}
