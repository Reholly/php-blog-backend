<?php

namespace App\Models\Results;

class SignInResult
{
    public bool $isSuccess;
    public ?string $error;
    public ?string $token;

    private function __construct(bool $isSuccess, ?string $token = null, ?string $error = null)
    {
        $this->isSuccess = $isSuccess;
        $this->token = $token;
        $this->error = $error;
    }

    public static function ok(string $token): SignInResult
    {
        return new SignInResult(true, $token);
    }

    public static function error(string $error): SignInResult
    {
        return new SignInResult(false, null, $error);
    }
}
