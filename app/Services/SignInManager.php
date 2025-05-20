<?php

namespace App\Services;

use App\Models\Results\SignInResult;
use Tymon\JWTAuth\Facades\JWTAuth;

class SignInManager
{
    public function signIn(array $credentials): SignInResult
    {
        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return SignInResult::error("Unauthorized");
        }

        return SignInResult::ok($token);
    }
}
