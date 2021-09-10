<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\State;

use App\Http\Controllers\V1\State\CreateStateController;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateStateTest extends TestCase
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

        $this->route = action(CreateStateController::class);
    }

    /** @test */
    public function create_state_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $data = ['name' => ''];

        $this->json('POST', $this->route, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid([
                'name' => 'required'
            ]);
    }

    /** @test */
    public function create_state_successfully(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $data = ['name' => $this->faker->unique()->text(70)];

        $this->json('POST', $this->route, $data)
            ->assertCreated()
            ->assertJsonStructure(['data' => [
                'id',
                'name'
            ]]);
    }
}
