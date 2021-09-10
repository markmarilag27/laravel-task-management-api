<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Response with access token, user data and authorization header
     *
     * @param string $accessToken
     * @param Model|User $user
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithAccessToken(string $accessToken, Model|User $user, int $statusCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => [
                'access_token'  => $accessToken,
                'user'          => new UserResource($user)
            ]
        ], $statusCode)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ]);
    }

    /**
     * Response with error message
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithErrorMessage(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $statusCode);
    }

    /**
     * Response with array of error
     *
     * @param string $message
     * @param array $errors
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithErrors(string $message, array $errors, int $statusCode = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
