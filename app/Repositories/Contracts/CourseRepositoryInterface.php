<?php

namespace App\Repositories\Contracts;

interface CourseRepositoryInterface
{
    /**
     * Get all courses.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Get all active courses.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allActive();

    /**
     * Find a course by ID.
     *
     * @param int $id
     * @return \App\Models\Course
     */
    public function find(int $id);

    /**
     * Create a new course.
     *
     * @param array $data
     * @return \App\Models\Course
     */
    public function create(array $data);

    /**
     * Update an existing course.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Course
     */
    public function update(int $id, array $data);
}
