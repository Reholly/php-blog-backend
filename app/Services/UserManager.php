<?php

namespace App\Services;

use App\Models\Results\GiveRoleResult;
use App\Models\Results\GiveRoleResultError;
use App\Models\User;
use App\Models\UserRole;
use App\Repository\UserRepository;

class UserManager
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findUserByID(int $id): User
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->save([
            'login' => $data['login'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'name' => $data['name'],
            'surname' => $data['surname'],
            'role' => UserRole::USER
        ]);
    }

    public function giveRoleToUser(User $user, UserRole|string $userRole): GiveRoleResult
    {
        if (!in_array($userRole, UserRole::getRoles())) {
            return GiveRoleResult::error(GiveRoleResultError::UNEXISTING_ROLE);
        }

        if ($userRole == UserRole::ADMIN) {
            return GiveRoleResult::error(GiveRoleResultError::GRANT_ADMIN);
        }

        $this->userRepository->update($user, [
            'role' => $userRole
        ]);

        return GiveRoleResult::ok();
    }

    public function deleteUserById(int $id): void
    {
        $user = $this->userRepository->findById($id);
        $this->userRepository->delete($user);
    }
}
