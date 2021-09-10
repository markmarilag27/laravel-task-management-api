<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\State;

use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateStateController extends Controller
{
    /**
     * Create a new CreateStateController constructor.
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
     * @return StateResource
     */
    public function __invoke(Request $request): StateResource
    {
        // Get the validated data from the request
        $payload = $request->validate(['name' => 'required|string|max:255|unique:states']);

        /** @var $state */
        $state = State::create($payload);

        return new StateResource($state);
    }
}
