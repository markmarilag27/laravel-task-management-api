<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\Task;

use App\Http\Controllers\V1\Task\ShowTaskController;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTaskTest extends TestCase
{
    /** @var string $route */
    protected string $route;

    /** @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $task */
    protected \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $task;

    /** @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $user */
    protected \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $user;

    /**
     * Override set up the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->task = Task::factory()->create(['user_id' => $this->user->id]);

        $this->route = action(ShowTaskController::class, ['task' => $this->task->uuid]);
    }

    /** @test */
    public function unauthorized_user_failed_to_get_task_information(): void
    {
        $this->assertGuest();

        $this->json('GET', $this->route)->assertUnauthorized();
    }

    /** @test */
    public function authorized_user_successfully_get_information(): void
    {
        Sanctum::actingAs($this->user);

        $this->assertAuthenticated();

        Task::factory()
            ->count(10)
            ->create([
                'parent_id' => $this->task->id,
                'user_id' => $this->user->id
            ]);

        $this->json('GET', $this->route)
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);
    }
}
