<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Enums\TaskState;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompletedTaskController extends Controller
{
    /**
     * Create a new LoginController constructor.
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        /** @var $tasks */
        $tasks = $request->user()
            ->tasks()
            ->select('created_at')
            ->whereMonth('created_at', now())
            ->whereRelation('state', 'name', TaskState::COMPLETED)
            ->get()
            ->groupBy(fn ($task) => $task->created_at->format('l'));

        return response()->json(['data' => $tasks]);
    }
}
