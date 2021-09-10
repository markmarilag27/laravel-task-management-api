<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\Task;

use App\Http\Controllers\V1\Task\RestoreTrashedTaskController;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RestoreTrashedTaskTest extends TestCase
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

        $this->route = action(RestoreTrashedTaskController::class);
    }

    /** @test */
    public function unauthorized_user_failed_to_restore_task(): void
    {
        $this->assertGuest();

        $this->json('POST', $this->route)->assertUnauthorized();
    }

    /** @test */
    public function restore_trashed_task_validation(): void
    {
        /** @var $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->assertAuthenticated();

        /** @var $task */
        $task = Task::factory()->create(['user_id' => $user->id]);
        $task->delete();

        /** @var $data */
        $data = ['uuid' => ''];

        $this->json('POST', $this->route, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid([
                'uuid' => 'required'
            ]);
    }

    /** @test */
    public function restore_trashed_task_successfully(): void
    {
        /** @var $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->assertAuthenticated();

        /** @var $task */
        $task = Task::factory()->create(['user_id' => $user->id]);
        $task->delete();

        /** @var $data */
        $data = ['uuid' => $task->uuid];

        $this->json('POST', $this->route, $data)
            ->assertOk()
            ->assertJsonStructure([
                'uuid',
                'is_subtask',
                'title',
                'body',
                'state',
                'sort_order',
                'total_subtask',
                'created_at'
            ]);
    }
}
