<?php

namespace App\Services;

use App\Repositories\Contracts\EbookRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookService
{
    protected $ebookRepository;
    protected $categoryRepository;
    protected $courseRepository;

    public function __construct(
        EbookRepositoryInterface $ebookRepository,
        CategoryRepositoryInterface $categoryRepository,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->ebookRepository = $ebookRepository;
        $this->categoryRepository = $categoryRepository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get paginated books with search and category/course active filters.
     *
     * @param array $queryParams
     * @param bool $onlyActive
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFilteredEbooks(array $queryParams, bool $onlyActive = true, int $perPage = 10)
    {
        $filters = [];

        if ($onlyActive) {
            $filters['active_categories'] = $this->categoryRepository->allActive()->pluck('name')->toArray();
            $filters['active_courses'] = $this->courseRepository->allActive()->pluck('name')->toArray();
        }

        if (!empty($queryParams['search'])) {
            $filters['search'] = $queryParams['search'];
        }

        if (!empty($queryParams['kategori'])) {
            $filters['category'] = $queryParams['kategori'];
        }

        if (!empty($queryParams['mata_kuliah'])) {
            $filters['related_course'] = $queryParams['mata_kuliah'];
        }

        return $this->ebookRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Get popular books.
     *
     * @param int $limit
     * @param bool $onlyActive
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPopularEbooks(int $limit = 5, bool $onlyActive = true)
    {
        $activeCategories = [];
        $activeCourses = [];

        if ($onlyActive) {
            $activeCategories = $this->categoryRepository->allActive()->pluck('name')->toArray();
            $activeCourses = $this->courseRepository->allActive()->pluck('name')->toArray();
        }

        return $this->ebookRepository->getPopular($limit, $activeCategories, $activeCourses);
    }

    /**
     * Get all active categories.
     */
    public function getActiveCategories()
    {
        return $this->categoryRepository->allActive();
    }

    /**
     * Get all active courses.
     */
    public function getActiveCourses()
    {
        return $this->courseRepository->allActive();
    }

    /**
     * Get distinct publishing years.
     */
    public function getPublishYears()
    {
        return $this->ebookRepository->getDistinctPublishYears();
    }

    /**
     * Get admin book list (without active filter constraints).
     */
    public function getAdminBookList(int $perPage = 10)
    {
        return $this->ebookRepository->getAllPaginated([], $perPage);
    }

    /**
     * Find book by ID.
     */
    public function findBook(int $id)
    {
        return $this->ebookRepository->find($id);
    }

    /**
     * Create a new book.
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile $pdfFile
     * @param \Illuminate\Http\UploadedFile|null $coverFile
     * @return \App\Models\Ebook
     */
    public function createEbook(array $data, $pdfFile, $coverFile = null)
    {
        // Save PDF file
        $pdfPath = $pdfFile->store('ebooks', 'public');

        // Save Cover file if present
        $coverPath = null;
        if ($coverFile) {
            $coverPath = $coverFile->store('covers', 'public');
        }

        return $this->ebookRepository->create([
            'title'          => $data['judul_buku'],
            'slug'           => Str::slug($data['judul_buku'] . '-' . time()),
            'publisher'      => $data['penerbit'],
            'publish_year'   => $data['tahun_terbit'],
            'category'       => $data['kategori'],
            'is_textbook'    => isset($data['is_buku_kuliah']),
            'related_course' => $data['mata_kuliah_terkait'] ?? null,
            'file_path'      => $pdfPath,
            'cover_path'     => $coverPath,
            'download_count' => 0
        ]);
    }

    /**
     * Update an existing book.
     *
     * @param int $id
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $pdfFile
     * @param \Illuminate\Http\UploadedFile|null $coverFile
     * @return \App\Models\Ebook
     */
    public function updateEbook(int $id, array $data, $pdfFile = null, $coverFile = null)
    {
        $book = $this->ebookRepository->find($id);
        $updateData = [];

        // Save cover image if uploaded
        if ($coverFile) {
            // Delete old cover
            if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
                Storage::disk('public')->delete($book->cover_path);
            }
            $updateData['cover_path'] = $coverFile->store('covers', 'public');
        }

        // Save PDF file if uploaded
        if ($pdfFile) {
            // Delete old PDF
            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                Storage::disk('public')->delete($book->file_path);
            }
            $updateData['file_path'] = $pdfFile->store('ebooks', 'public');
        }

        // Map request inputs to DB columns
        $updateData['title'] = $data['title'];
        $updateData['publisher'] = $data['publisher'] ?? null;
        $updateData['publish_year'] = $data['publish_year'] ?? null;
        $updateData['category'] = $data['category'];
        $updateData['related_course'] = $data['related_course'] ?? null;

        return $this->ebookRepository->update($id, $updateData);
    }

    /**
     * Delete a book and its files.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEbook(int $id)
    {
        $book = $this->ebookRepository->find($id);

        // Delete cover file
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }

        // Delete PDF file
        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }

        return $this->ebookRepository->delete($id);
    }

    /**
     * Increment downloads and return storage download response info.
     *
     * @param int $id
     * @return array
     */
    public function getDownloadResponse(int $id)
    {
        $book = $this->ebookRepository->find($id);
        $this->ebookRepository->incrementDownloads($id);

        return [
            'file_path' => $book->file_path,
            'filename'  => $book->slug . '.pdf'
        ];
    }
}
