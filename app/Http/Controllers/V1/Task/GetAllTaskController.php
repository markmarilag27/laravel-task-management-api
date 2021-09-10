<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class GetAllTaskController extends Controller
{
    /**
     * Create a new GetAllTaskController constructor.
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        /** @var $userId */
        $userId = $request->user()->id;

        /** @var $tasks */
        $tasks = Task::query()
            ->hasFiltered()
            ->belongsToOwnerId($userId)
            ->onlyTopLevel()
            ->getAllTasks()
            ->simplePaginate($request->per_page);

        return TaskResource::collection($tasks);
    }
}
