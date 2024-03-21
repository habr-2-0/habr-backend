<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginRegisterController extends Controller
{

    /**
     * Register a new user.
     * @param App\Http\Requests\RegisterRequest $request
     * @param App\Services\UserService $service
     * @return \Illuminate\Http\Response
     */

    public function register(RegisterRequest $request, UserService $service): JsonResponse
    {
        $validated = $request->validated();

        $user = $service->create(UserDTO::fromArray($validated));

        $data['token'] = $user->createToken($request->validated('email'))->plainTextToken;
        $data['user'] = $user;

        $response = [
            'status' => __('messages.status_success'),
            'message' => __('messages.user_created'),
            'data' => $data,
        ];


        return response()->json($response, 201);
    }

    /**
     * Authenticate the user.
     *
     * @param App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();

        // Check email exist
        $user = User::where('email', $request->email)->first();

        // Check password
        if (!$user || !Hash::check($request->validated('password'), $user->password)) {
            return response()->json([
                'status' => __('messages.status_failed'),
                'message' => __('messages.invalid_credentials')
            ], 401);
        }

        $data['token'] = $user->createToken($request->validated('email'))->plainTextToken;
        $data['user'] = $user;

        $response = [
            'status' => __('messages.status_success'),
            'message' => __('messages.login_success'),
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    /**
     * Log out the user from application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => __('messages.status_success'),
            'message' => __('messages.logout_success')
        ], 200);
    }
}
