<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories.
     */
    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return \App\Models\Category
     */
    public function createCategory(array $data)
    {
        return $this->categoryRepository->create([
            'name' => $data['name']
        ]);
    }

    /**
     * Toggle the active status of a category.
     *
     * @param int $id
     * @return \App\Models\Category
     */
    public function toggleCategoryStatus(int $id)
    {
        $category = $this->categoryRepository->find($id);
        
        return $this->categoryRepository->update($id, [
            'is_active' => !$category->is_active
        ]);
    }
}
