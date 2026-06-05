<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all()
    {
        return Category::latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function allActive()
    {
        return Category::where('is_active', true)->get();
    }

    /**
     * @inheritDoc
     */
    public function find(int $id)
    {
        return Category::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return Category::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data)
    {
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }
}
