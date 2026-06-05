<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Services\EbookService;
use App\Services\CategoryService;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    protected $ebookService;
    protected $categoryService;
    protected $courseService;

    public function __construct(
        EbookService $ebookService,
        CategoryService $categoryService,
        CourseService $courseService
    ) {
        $this->ebookService = $ebookService;
        $this->categoryService = $categoryService;
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of books on the dashboard.
     */
    public function index(Request $request)
    {
        // Get paginated books matching active categories and courses
        $ebooks = $this->ebookService->getFilteredEbooks($request->all(), true, 10);

        // Fetch dropdown options for filter form
        $categories = $this->ebookService->getActiveCategories();
        $courses = $this->ebookService->getActiveCourses();
        $years = $this->ebookService->getPublishYears();

        // Get popular books
        $popularBooks = $this->ebookService->getPopularEbooks(5, true);

        return view('dashboard', compact('ebooks', 'categories', 'courses', 'years', 'popularBooks'));
    }

    /**
     * Show the form for creating a new book (user upload).
     */
    public function create()
    {
        $categories = $this->ebookService->getActiveCategories();
        $courses = $this->ebookService->getActiveCourses();

        return view('ebooks.create', compact('categories', 'courses'));
    }

    /**
     * Store a newly created book.
     */
    public function store(StoreBookRequest $request)
    {
        $this->ebookService->createEbook(
            $request->validated(),
            $request->file('file_pdf'),
            $request->file('cover_image')
        );

        return redirect()->route('dashboard')->with('success', 'Buku berhasil diupload!');
    }

    /**
     * Download the specified book.
     */
    public function download($id)
    {
        $downloadInfo = $this->ebookService->getDownloadResponse($id);

        return Storage::disk('public')->download($downloadInfo['file_path'], $downloadInfo['filename']);
    }

    /**
     * Preview the specified book.
     */
    public function preview($id)
    {
        $ebook = $this->ebookService->findBook($id);

        return view('ebooks.preview', compact('ebook'));
    }
}
