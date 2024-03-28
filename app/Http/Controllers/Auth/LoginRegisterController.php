<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserDTO;
use App\Exceptions\AuthException;
use App\Exceptions\BusinessException;
use App\Exceptions\DuplicateEntryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginRegisterController extends Controller
{
    /**
     * Register a new user.
     * @param UserRegisterRequest $request
     * @param UserService $service
     * @return JsonResponse
     * @throws AuthException
     * @throws DuplicateEntryException
     */

    public function register(UserRegisterRequest $request, UserService $service): JsonResponse
    {
        $validated = $request->validated();

        $user = $service->create(UserDTO::fromArray($validated));

        $expiresAt = now()->addHour();

        $tokenName = $request->validated('email') . '_' . $user->id;
        $data['token'] = $user->createToken($tokenName, ['*'], $expiresAt)->plainTextToken;

        $data['user'] = $user;

        throw new AuthException(
            __('messages.registration_success'),
            Response::HTTP_CREATED,
            __('messages.status_success'),
            $data
        );
    }

    /**
     * Authenticate the user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws AuthException|BusinessException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();

        // Check email exist
        $user = User::where('email', $request->email)->first();

        // Check password
        if (!$user || !Hash::check($request->validated('password'), $user->password)) {
            throw new BusinessException(__('messages.invalid_credentials'), Response::HTTP_UNAUTHORIZED);
        }

        $data['token'] = $user->createToken($request->validated('email'))->plainTextToken;
        $data['user'] = $user;

        throw new AuthException(
            __('messages.login_success'),
            Response::HTTP_OK,
            __('messages.status_success'),
            $data
        );
    }

    /**
     * Log out the user from application.
     *
     * @return JsonResponse
     * @throws BusinessException|AuthException
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        throw new AuthException(
            __('messages.logout_success'),
            Response::HTTP_OK,
            __('messages.status_success'),
        );
    }
}
