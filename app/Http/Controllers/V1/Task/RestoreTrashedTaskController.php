<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class RestoreTrashedTaskController extends Controller
{
    /**
     * Create a new RestoreTrashedTaskController constructor.
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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        // Get the validated data from the request
        $payload = $request->validate(['uuid' => 'required|string|max:36']);

        $task = Task::query()
            ->onlyTrashed()
            ->where('uuid', $payload['uuid'])
            ->firstOrFail();

        $task->restore();

        $task->load('state')->withCount('subTasks');

        return TaskResource::make($task)->resolve();
    }
}
