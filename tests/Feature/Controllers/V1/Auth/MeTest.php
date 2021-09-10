<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\Auth;

use App\Http\Controllers\V1\Auth\MeController;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MeTest extends TestCase
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

        $this->route = action(MeController::class);
    }

    /** @test */
    public function unauthenticated_user_failed_to_access_its_information(): void
    {
        $this->assertGuest();

        $this->json('GET', $this->route)
            ->assertUnauthorized()
            ->assertJsonStructure([
                'message'
            ]);
    }

    /** @test */
    public function authenticated_user_successfully_access_its_information(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $this->json('GET', $this->route)
            ->assertOk()
            ->assertJsonStructure(['data' => [
                'uuid',
                'email',
                'name'
            ]]);
    }
}
