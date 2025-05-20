<?php

namespace App\Models\Results;

class GiveRoleResult
{
    public bool $isSuccess;
    public GiveRoleResultError|string|null $error;

    private function __construct(bool $isSuccess, GiveRoleResultError|string|null $error = GiveRoleResultError::NONE)
    {
        $this->isSuccess = $isSuccess;
        $this->error = $error;
    }

    public static function ok(): GiveRoleResult
    {
        return new GiveRoleResult(true, null);
    }

    public static function error(GiveRoleResultError|string $error): GiveRoleResult
    {
        return new GiveRoleResult(false, $error);
    }
}
