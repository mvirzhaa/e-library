<?php

namespace App\Services;

use App\Repositories\Contracts\CourseRepositoryInterface;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get all courses.
     */
    public function getAllCourses()
    {
        return $this->courseRepository->all();
    }

    /**
     * Create a new course.
     *
     * @param array $data
     * @return \App\Models\Course
     */
    public function createCourse(array $data)
    {
        return $this->courseRepository->create([
            'name' => $data['name']
        ]);
    }

    /**
     * Toggle the active status of a course.
     *
     * @param int $id
     * @return \App\Models\Course
     */
    public function toggleCourseStatus(int $id)
    {
        $course = $this->courseRepository->find($id);

        return $this->courseRepository->update($id, [
            'is_active' => !$course->is_active
        ]);
    }
}
