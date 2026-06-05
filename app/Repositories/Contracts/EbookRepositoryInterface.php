<?php

namespace App\Repositories\Contracts;

interface EbookRepositoryInterface
{
    /**
     * Get paginated list of ebooks with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(array $filters, int $perPage = 10);

    /**
     * Find a book by ID.
     *
     * @param int $id
     * @return \App\Models\Ebook
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(int $id);

    /**
     * Create a new book.
     *
     * @param array $data
     * @return \App\Models\Ebook
     */
    public function create(array $data);

    /**
     * Update an existing book.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Ebook
     */
    public function update(int $id, array $data);

    /**
     * Delete a book.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Count total ebooks.
     *
     * @return int
     */
    public function countAll();

    /**
     * Sum total downloads.
     *
     * @return int
     */
    public function sumDownloads();

    /**
     * Increment the download count of an ebook.
     *
     * @param int $id
     * @return bool
     */
    public function incrementDownloads(int $id);

    /**
     * Get popular ebooks filtered by active categories and courses.
     *
     * @param int $limit
     * @param array $activeCategories
     * @param array $activeCourses
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPopular(int $limit = 5, array $activeCategories = [], array $activeCourses = []);

    /**
     * Get distinct list of publishing years.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDistinctPublishYears();
}
