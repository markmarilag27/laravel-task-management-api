<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReOrderTaskController extends Controller
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
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function __invoke(Request $request): mixed
    {
        // Get the validated data from the request
        $payload = $request->validate(['tasks' => 'required|array']);

        return DB::transaction(function () use ($payload) {
            collect($payload['tasks'])->each(function ($task) {
                DB::table('tasks')
                    ->where('uuid', $task['uuid'])
                    ->update([
                        'sort_order' => $task['sort_order']
                    ]);
            });
        });
    }
}
