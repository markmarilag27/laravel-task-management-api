<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\Auth;

use App\Http\Controllers\V1\Auth\LogoutController;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
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

        $this->route = action(LogoutController::class);
    }

    /** @test */
    public function unauthorized_user_failed_to_logout(): void
    {
        $this->assertGuest();

        $this->json('POST', $this->route)
            ->assertUnauthorized()
            ->assertJsonStructure([
                'message'
            ]);
    }

    /** @test */
    public function authenticated_user_successfully_logout(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $this->json('POST', $this->route)->assertNoContent();
    }
}
