<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\Task;

use App\Http\Controllers\V1\Task\CreateTaskController;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTaskTest extends TestCase
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

        $this->route = action(CreateTaskController::class);
    }

    /** @test */
    public function create_task_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $data = [
            'title'         => '',
            'body'          => '',
            'state_id'      => null,
            'sort_order'    => 1
        ];

        $this->json('POST', $this->route, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid([
                'title',
                'body',
                'state_id'
            ]);
    }

    /** @test */
    public function create_task_successfully(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $data = [
            'title'         => $this->faker->text(70),
            'body'          => $this->faker->paragraph,
            'state_id'      => 1,
            'sort_order'    => 1
        ];

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

    /** @test */
    public function create_subtask_successfully(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $task = Task::factory()->create();

        $data = [
            'uuid'          => $task->uuid,
            'title'         => $this->faker->text(70),
            'body'          => $this->faker->paragraph,
            'state_id'      => 1,
            'sort_order'    => 1
        ];

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
