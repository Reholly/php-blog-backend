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

        if ($data['role'] === UserRole::ADMIN) {
            return response()->json(['message' => 'Нельзя выдать роль администратора'],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($data['to'] === auth()->user()->id) {
            return response()->json(['message' => 'Нельзя выдать роль самому себе'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userToGrant = User::findOrFail($data['to']);
        $userToGrant->update(['role' => $data['role']]);

        return response()->json();
    }

    public function deleteUser($id): JsonResponse
    {
        if ($id === auth()->user()->id) {
            return response()->json(['message' => 'Нельзя забанить самого себя'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
