<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function signUp(Request $request): JsonResponse
    {
        error_log(print_r($request->all(), true));

        try {
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

            User::create([
                'login' => $data['login'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'name' => $data['name'],
                'surname' => $data['surname'],
                'role' => UserRole::USER
            ]);

            return response()->json(null, 201);
        } catch (Exception $exception) {
            error_log($exception);
            return response()->json($exception, 500);
        }
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

        if (!$token = JWTAuth::attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }
}
