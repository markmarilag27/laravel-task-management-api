<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\Task;

use App\Http\Controllers\V1\Task\GetAllTaskController;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetAllTaskTest extends TestCase
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

        $this->route = action(GetAllTaskController::class);
    }

    /** @test */
    public function unauthorized_user_failed_to_get_all_task_information(): void
    {
        $this->assertGuest();

        $this->json('GET', $this->route)->assertUnauthorized();
    }

    /** @test */
    public function authorized_user_successfully_get_all_task_information(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->assertAuthenticated();

        Task::factory()->count(10)->create(['user_id' => $user->id]);

        $this->json('GET', $this->route)
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);
    }
}
