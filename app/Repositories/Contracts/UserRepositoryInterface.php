<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    /**
     * Get all users paginated.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10);

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return \App\Models\User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(int $id);

    /**
     * Update user data.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\User
     */
    public function update(int $id, array $data);

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Count users by their role.
     *
     * @param string $role
     * @return int
     */
    public function countByRole(string $role);
}
