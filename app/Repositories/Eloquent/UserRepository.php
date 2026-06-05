<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getAllPaginated(int $perPage = 10)
    {
        return User::latest()->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id)
    {
        $user = $this->find($id);
        return $user->delete();
    }

    /**
     * @inheritDoc
     */
    public function countByRole(string $role)
    {
        return User::where('role', $role)->count();
    }
}
