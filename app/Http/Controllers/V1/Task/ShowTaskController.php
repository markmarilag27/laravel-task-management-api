<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowTaskController extends Controller
{
    /**
     * Create a new ShowTaskController constructor.
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
     * @param Task $task
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request, Task $task): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        /** @var $user */
        $user = $request->user();

        if ($user->cannot('view', $task)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        /** @var $tasks */
        $tasks = Task::query()
            ->select([
                'uuid',
                'title',
                'body',
                'state_id',
                'sort_order',
                'created_at'
            ])
            ->hasFiltered()
            ->belongsToOwnerId($user->id)
            ->belongsToTaskId($task->id)
            ->withCount('subTasks')
            ->with('state')
            ->latest('parent_id')
            ->simplePaginate($request->per_page);

        return TaskResource::collection($tasks);
    }
}
