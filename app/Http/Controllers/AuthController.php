<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signUp(Request $request): JsonResponse
    {
        error_log(print_r($request->all(), true));

        try {
            $requestContent = $request->validate([
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
                'login' => $requestContent['login'],
                'password' => password_hash($requestContent['password'], PASSWORD_BCRYPT),
                'name' => $requestContent['name'],
                'surname' => $requestContent['surname'],
                'role' => UserRole::USER
            ]);

            return response()->json(null, 201);
        } catch (Exception $exception) {
            error_log($exception);
            return response()->json($exception, 500);
        }
    }
}
