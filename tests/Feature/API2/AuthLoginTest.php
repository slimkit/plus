<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthLoginTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
    }

    /**
     * Test User ID login.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testUserLogin()
    {
        $response = $this->json('POST', 'api/v2/auth/login', [
            'login' => $this->user->id,
            'password' => 'secret'
        ]);

        $this->assertLoginResponse($response);
    }

    /**
     * Assert login response.
     *
     * @param [type] $response
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function assertLoginResponse($response)
    {
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }
}
