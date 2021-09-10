<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Create a new LoginController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:10,1');
    }

    /**
     * Handle the incoming request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        // Get the validated data from the request
        $payload = $request->validated();

        /** @var $user */
        $user = User::query()
            ->where('email', $payload['email'])
            ->first();

        // Response error if user is null
        if (blank($user)) {
            /** @var $message */
            $message = trans('passwords.user');

            return $this->responseWithErrors($message, [ 'email' => [$message] ]);
        }

        // Response error if payload password does not match selected user
        if (! Hash::check($payload['password'], $user->password)) {
            /** @var $message */
            $message = trans('auth.password');

            return $this->responseWithErrors($message, [ 'password' => [$message] ]);
        }

        /** @var $accessToken */
        $accessToken = $user->createToken($request->userAgent())->plainTextToken;

        return $this->responseWithAccessToken($accessToken, $user);
    }
}
