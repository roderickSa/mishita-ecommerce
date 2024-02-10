<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserAuthController extends Controller
{
    public function register(UserRegisterRequest $userRegisterRequest): JsonResponse
    {
        $userRequestValidated = $userRegisterRequest->validated();

        $userRequestValidated["password"] = bcrypt($userRequestValidated["password"]);

        $user = User::create($userRequestValidated);

        $token_data = $user->createToken(env("SECRET_TOKEN_APP"));

        $access_token = $token_data->accessToken;

        $expires_in = $token_data->token->expires_at->diffInSeconds(Carbon::now());

        event(new UserCreated($userRegisterRequest->email));

        return response()->json(["user" => $user, "token" => [
            "access_token" => $access_token,
            "expires_in" => $expires_in,
            'token_type' => 'Bearer',
        ]], Response::HTTP_CREATED);
    }

    public function login(UserLoginRequest $userLoginRequest): JsonResponse
    {
        $userLoginRequest->authenticate();

        $token_data = auth()->user()->createToken(env("SECRET_TOKEN_APP"));

        $access_token = $token_data->accessToken;

        $expires_in = $token_data->token->expires_at->diffInSeconds(Carbon::now());

        return response()->json(["user" => auth()->user(), "token" => [
            "access_token" => $access_token,
            "expires_in" => $expires_in,
            'token_type' => 'Bearer',
        ]], Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        $token = auth()->user()->token();

        $token->revoke();

        $token->delete();

        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }
}
