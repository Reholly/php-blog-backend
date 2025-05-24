<?php

namespace App\Http\Controllers;

use App\Models\Results\GiveRoleResultError;
use App\Services\UserManager;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    protected UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function grantRole(): JsonResponse
    {
        $data = request()->validate([
            'role' => 'required|string',
            'to' => 'required|integer'
        ]);

        if ($data['to'] === auth()->user()->id) {
            return response()->json(['message' => 'Нельзя выдать роль самому себе'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userToGrant = $this->userManager->findUserByID($data['to']);
        $result = $this->userManager->giveRoleToUser($userToGrant, $data['role']);
        if (!$result->isSuccess) {
            switch ($result->error) {
                case GiveRoleResultError::UNEXISTING_ROLE:
                    return response()->json(['message' => $result->error], Response::HTTP_NOT_FOUND);
                case GiveRoleResultError::GRANT_ADMIN:
                    return response()->json(['message' => $result->error], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return response()->json(null, Response::HTTP_OK);
    }

    public function deleteUser($id): JsonResponse
    {
        if ($id == auth()->user()->id) {
            return response()->json(['message' => 'Нельзя забанить самого себя'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->userManager->deleteUserById($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
