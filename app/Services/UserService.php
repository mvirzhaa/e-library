<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\EbookRepositoryInterface;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $userRepository;
    protected $ebookRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EbookRepositoryInterface $ebookRepository
    ) {
        $this->userRepository = $userRepository;
        $this->ebookRepository = $ebookRepository;
    }

    /**
     * Get dashboard stats.
     *
     * @return array
     */
    public function getDashboardStats()
    {
        return [
            'buku' => $this->ebookRepository->countAll(),
            'user' => $this->userRepository->countByRole('user'),
            'admin' => $this->userRepository->countByRole('admin'),
            'download' => $this->ebookRepository->sumDownloads(),
        ];
    }

    /**
     * Get users list paginated.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsersPaginated(int $perPage = 10)
    {
        return $this->userRepository->getAllPaginated($perPage);
    }

    /**
     * Update user role and status with safety rules.
     *
     * @param int $id
     * @param array $data
     * @param User $executor
     * @return User
     * @throws ValidationException
     */
    public function updateUserRoleAndStatus(int $id, array $data, User $executor)
    {
        $user = $this->userRepository->find($id);

        // --- BENTENG KEAMANAN ANTI KUDETA ---
        // 1. Admin dilarang mengubah data Superadmin
        if ($executor->role === 'admin' && $user->role === 'superadmin') {
            throw ValidationException::withMessages([
                'error' => 'Lancang! Admin tidak diizinkan mengubah data Superadmin.'
            ]);
        }

        // 2. Admin dilarang mengangkat Superadmin baru
        if ($executor->role === 'admin' && isset($data['role']) && $data['role'] === 'superadmin') {
            throw ValidationException::withMessages([
                'error' => 'Akses Ilegal: Hanya Superadmin yang bisa mengangkat Superadmin baru.'
            ]);
        }

        // 3. Cegah ganti role sendiri dari sini
        if ($executor->id === $user->id && isset($data['role']) && $data['role'] !== $executor->role) {
            throw ValidationException::withMessages([
                'error' => 'Gunakan menu profil untuk mengubah data Anda sendiri.'
            ]);
        }

        return $this->userRepository->update($id, [
            'role' => $data['role'] ?? $user->role,
            'is_active' => $data['is_active'] ?? $user->is_active,
        ]);
    }

    /**
     * Delete a user with safety checks.
     *
     * @param int $id
     * @param User $executor
     * @return bool
     * @throws ValidationException
     */
    public function deleteUser(int $id, User $executor)
    {
        $user = $this->userRepository->find($id);

        // --- BENTENG KEAMANAN ANTI KUDETA ---
        if ($executor->role === 'admin' && $user->role === 'superadmin') {
            throw ValidationException::withMessages([
                'error' => 'Pemberontakan! Admin tidak bisa menghapus Superadmin.'
            ]);
        }

        if ($executor->id === $user->id) {
            throw ValidationException::withMessages([
                'error' => 'Anda tidak bisa menghapus akun Anda sendiri.'
            ]);
        }

        return $this->userRepository->delete($id);
    }
}
