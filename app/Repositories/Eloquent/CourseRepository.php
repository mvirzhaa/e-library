<?php

namespace App\Repositories\Eloquent;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all()
    {
        return Course::latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function allActive()
    {
        return Course::where('is_active', true)->get();
    }

    /**
     * @inheritDoc
     */
    public function find(int $id)
    {
        return Course::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return Course::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data)
    {
        $course = $this->find($id);
        $course->update($data);
        return $course;
    }
}
