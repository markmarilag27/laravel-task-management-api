<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\Auth;

use App\Http\Controllers\V1\Auth\LoginController;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @var string $route */
    protected string $route;

    /**
     * Override set up the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->route = action(LoginController::class);
    }

    /** @test */
    public function login_failed_required_fields_is_missing(): void
    {
        /** @var $data */
        $data = [
            'email'     => '',
            'password'  => ''
        ];

        $this->json('POST', $this->route, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid([
                'email' => 'required',
                'password' => 'required'
            ]);
    }

    /** @test */
    public function login_success_using_correct_credentials(): void
    {
        /** @var $user */
        $user = User::factory()->create();

        /** @var $data */
        $data = [
            'email'     => $user->email,
            'password'  => 'password'
        ];

        $this->json('POST', $this->route, $data)
            ->assertOk()
            ->assertValid([
                'access_token',
                'user'
            ])
            ->assertJsonStructure(['data' => [
                'user' => [
                    'uuid',
                    'email',
                    'name'
                ]
            ]]);
    }
}
