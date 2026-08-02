<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

use App\Services\AuthService;


class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {//Dependency Injection
        $this->authService = $authService;
    }
   public function register(RegisterRequest $request)
    {
        $user= $this->authService->register($request->validated());
        return response()->json($user,201);

    } 
    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request->validated());

        return response()->json($user, 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully.',
        ], 200);
    }

}

