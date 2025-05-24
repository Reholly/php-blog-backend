<?php

namespace App\Http\Controllers;

use App\Services\SignInManager;
use App\Services\UserManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected UserManager $userManager;
    protected SignInManager $signInManager;

    public function __construct(UserManager $userManager, SignInManager $signInManager)
    {
        $this->userManager = $userManager;
        $this->signInManager = $signInManager;
    }

    public function signUp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'login' => 'required|unique:users',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'name' => 'required',
            'surname' => 'required',
        ], [
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву и одну цифру.'
        ]);

        $user = $this->userManager->createUser($data);

        return response()->json($user, 201);
    }

    public function signIn(Request $request): JsonResponse
    {
        $data = $request->validate([
            'login' => 'required|exists:users',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву и одну цифру.'
        ]);

        $tokenResult = $this->signInManager->signIn($data);

        return $tokenResult->isSuccess
            ? response()->json(['token' => $tokenResult->token])
            : response()->json(['message' => $tokenResult->error], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
