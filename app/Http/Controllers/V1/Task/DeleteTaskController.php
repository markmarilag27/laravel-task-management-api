<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class DeleteTaskController extends Controller
{
    /**
     * Create a new DeleteTaskController constructor.
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
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Task $task): \Illuminate\Http\Response
    {
        // Soft delete the task
        $task->delete();

        return response()->noContent();
    }
}
