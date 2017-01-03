<?php

namespace Ts\Test;

use App\Exceptions\MessageResponseBody;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Laravel');
    }

    public function testApiAuthGetPhoneCode()
    {
        $this->postJson('/api/v1/auth/phone/send-code')
             ->seeJson(app(MessageResponseBody::class, [
                'code' => 1000,
            ])->getBody());
    }
}
