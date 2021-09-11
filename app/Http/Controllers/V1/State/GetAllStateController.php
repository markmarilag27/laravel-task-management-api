<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\State;

use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;

class GetAllStateController extends Controller
{
    /**
     * Create a new GetAllStateController constructor.
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
        /** @var $state */
        $state = State::query()
            ->latest('id')
            ->hasFiltered()
            ->simplePaginate($request->per_page);

        return StateResource::collection($state);
    }
}
