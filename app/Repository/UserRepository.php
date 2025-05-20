<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function save(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
