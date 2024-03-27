<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse
    {

        if ($e instanceof AuthenticationException) {
            if (Auth::check()) {
                $user = Auth::user();

                if ($user->token()) {
                    $tokenExpiration = Carbon::parse($user->token()->expires_at);

                    if ($tokenExpiration->isPast()) {
                        return response()->json(
                            [
                                'error' => __('messages.token_expired')
                            ],
                            Response::HTTP_UNAUTHORIZED
                        );
                    }
                } else {
                    return response()->json(
                        [
                            'error' => __('messages.no_token_provided')
                        ],
                        Response::HTTP_UNAUTHORIZED
                    );
                }
            } else {
                return response()->json(
                    [
                        'error' => __('messages.unauthorized')
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }
        }


        return parent::render($request, $e);
    }
}
