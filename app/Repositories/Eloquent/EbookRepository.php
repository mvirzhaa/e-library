<?php

namespace App\Repositories\Eloquent;

use App\Models\Ebook;
use App\Repositories\Contracts\EbookRepositoryInterface;

class EbookRepository implements EbookRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getAllPaginated(array $filters, int $perPage = 10)
    {
        $query = Ebook::query();

        // Filter by active categories
        if (!empty($filters['active_categories'])) {
            $query->whereIn('category', $filters['active_categories']);
        }

        // Filter by active courses
        if (isset($filters['active_courses'])) {
            $activeCourses = $filters['active_courses'];
            $query->where(function ($q) use ($activeCourses) {
                $q->whereIn('related_course', $activeCourses)
                  ->orWhereNull('related_course')
                  ->orWhere('related_course', '');
            });
        }

        // Search text
        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        // Category filter (selected category)
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Related course filter (selected course)
        if (!empty($filters['related_course'])) {
            $query->where('related_course', $filters['related_course']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id)
    {
        return Ebook::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return Ebook::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data)
    {
        $book = $this->find($id);
        $book->update($data);
        return $book;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id)
    {
        $book = $this->find($id);
        return $book->delete();
    }

    /**
     * @inheritDoc
     */
    public function countAll()
    {
        return Ebook::count();
    }

    /**
     * @inheritDoc
     */
    public function sumDownloads()
    {
        return Ebook::sum('download_count');
    }

    /**
     * @inheritDoc
     */
    public function incrementDownloads(int $id)
    {
        $book = $this->find($id);
        return $book->increment('download_count');
    }

    /**
     * @inheritDoc
     */
    public function getPopular(int $limit = 5, array $activeCategories = [], array $activeCourses = [])
    {
        $query = Ebook::query();

        if (!empty($activeCategories)) {
            $query->whereIn('category', $activeCategories);
        }

        if (!empty($activeCourses)) {
            $query->where(function ($q) use ($activeCourses) {
                $q->whereIn('related_course', $activeCourses)
                  ->orWhereNull('related_course')
                  ->orWhere('related_course', '');
            });
        }

        return $query->orderBy('download_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getDistinctPublishYears()
    {
        return Ebook::select('publish_year')
            ->distinct()
            ->orderBy('publish_year', 'desc')
            ->pluck('publish_year');
    }
}
