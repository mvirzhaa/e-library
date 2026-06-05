<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Get all active categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allActive();

    /**
     * Find a category by ID.
     *
     * @param int $id
     * @return \App\Models\Category
     */
    public function find(int $id);

    /**
     * Create a new category.
     *
     * @param array $data
     * @return \App\Models\Category
     */
    public function create(array $data);

    /**
     * Update an existing category.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Category
     */
    public function update(int $id, array $data);
}
