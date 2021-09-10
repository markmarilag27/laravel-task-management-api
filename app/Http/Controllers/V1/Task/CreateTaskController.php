<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isInstanceOf;

class CreateTaskController extends Controller
{
    /**
     * Create a new CreateTaskController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Handle the incoming request.
     *
     * @param CreateTaskRequest $request
     * @return array
     */
    public function __invoke(CreateTaskRequest $request): array
    {
        // Get the validated data from the request
        $payload = $request->validated();

        // Set the user id to the current user
        $payload['user_id'] = $request->user()->id;

        if ($request->has('uuid') && filled($payload['uuid'])) {
            $payload['parent_id'] = Task::query()
                ->where('uuid', $payload['uuid'])
                ->first()->id;
        }

        /** @var $task */
        $task = Task::create($payload);

        $task->load('state')->withCount('subTasks');

        return TaskResource::make($task)->resolve();
    }
}
