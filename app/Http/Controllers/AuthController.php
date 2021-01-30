<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\Contracts\Service as AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * Authentication service.
     *
     * @var \App\Services\Auth\Service
     */
    protected $authService;

    /**
     * Auth controller constructor.
     *
     * @param \App\Services\Auth\Contracts\Service $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login user to application.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $authorized = $this->authService->attempt(
            $request->input('login'),
            $request->input('password')
        );

        if (! $authorized) {
            return response()->json([
                'status' => 'failure',
            ]);
        };

        return response()->json([
            'status' => 'success',
            'token'  => $this->authService->generateToken()->toString(),
        ]);
    }
}
