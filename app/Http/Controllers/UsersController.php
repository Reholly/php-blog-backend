<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function grantRole(): JsonResponse
    {
        $data = request()->validate([
            'role' => 'required|string',
            'to' => 'required|integer'
        ]);

        if (!in_array($data['role'], UserRole::getRoles())) {
            return response()->json(['message' => 'Такой роли не существует'], Response::HTTP_BAD_REQUEST);
        }

        $userToGrant = User::findOrFail($data['to']);
        $userToGrant->update(['role' => $data['role']]);

        return response()->json();
    }
    // TODO remove role
    // TODO delete user
}
