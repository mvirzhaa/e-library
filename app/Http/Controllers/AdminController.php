<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Services\EbookService;
use App\Services\CategoryService;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    protected $userService;
    protected $ebookService;
    protected $categoryService;
    protected $courseService;

    public function __construct(
        UserService $userService,
        EbookService $ebookService,
        CategoryService $categoryService,
        CourseService $courseService
    ) {
        $this->userService = $userService;
        $this->ebookService = $ebookService;
        $this->categoryService = $categoryService;
        $this->courseService = $courseService;
    }

    /**
     * Display the Admin Dashboard statistics.
     */
    public function index()
    {
        $stats = $this->userService->getDashboardStats();
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display a listing of books for management.
     */
    public function books()
    {
        $books = $this->ebookService->getAdminBookList(10);
        return view('admin.books', compact('books'));
    }

    /**
     * Show the form for adding a new book.
     */
    public function createBook()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Maaf, hanya Dosen dan Admin yang boleh mengupload buku.');
        }

        $categories = $this->ebookService->getActiveCategories();
        $courses = $this->ebookService->getActiveCourses();

        return view('admin.books_create', compact('categories', 'courses'));
    }

    /**
     * Store a newly created book.
     */
    public function storeBook(StoreBookRequest $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Maaf, hanya Dosen dan Admin yang boleh mengupload buku.');
        }

        $this->ebookService->createEbook(
            $request->validated(),
            $request->file('file_pdf'),
            $request->file('cover_image')
        );

        return redirect()->route('admin.books')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    /**
     * Display a listing of registered users.
     */
    public function users()
    {
        if (auth()->user()->role === 'dosen') {
            abort(403, 'Akses Ditolak: Dosen hanya diizinkan mengelola buku.');
        }

        $users = $this->userService->getUsersPaginated(10);
        return view('admin.users', compact('users'));
    }

    /**
     * Delete the specified book.
     */
    public function destroyBook($id)
    {
        $this->ebookService->deleteEbook($id);
        return back()->with('success', 'Buku berhasil dihapus.');
    }

    /**
     * Delete the specified user.
     */
    public function destroyUser($id)
    {
        try {
            $this->userService->deleteUser($id, auth()->user());
            return back()->with('success', 'Akun berhasil dihapus selamanya.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return back()->with('error', $errors['error'][0] ?? 'Gagal menghapus user.');
        }
    }

    /**
     * Display all categories.
     */
    public function categories()
    {
        if (auth()->user()->role === 'dosen') {
            abort(403, 'Akses Ditolak: Dosen hanya diizinkan mengelola buku.');
        }

        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(StoreCategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Toggle the status of a category.
     */
    public function toggleCategory($id)
    {
        $category = $this->categoryService->toggleCategoryStatus($id);
        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kategori berhasil {$status}.");
    }

    /**
     * Display all courses.
     */
    public function courses()
    {
        if (auth()->user()->role === 'dosen') {
            abort(403, 'Akses Ditolak: Dosen hanya diizinkan mengelola buku.');
        }

        $courses = $this->courseService->getAllCourses();
        return view('admin.courses', compact('courses'));
    }

    /**
     * Store a new course.
     */
    public function storeCourse(StoreCourseRequest $request)
    {
        $this->courseService->createCourse($request->validated());
        return back()->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    /**
     * Toggle the status of a course.
     */
    public function toggleCourse($id)
    {
        $course = $this->courseService->toggleCourseStatus($id);
        $status = $course->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Mata Kuliah berhasil {$status}.");
    }

    /**
     * Update user role and status.
     */
    public function updateRole(UpdateUserRequest $request, $id)
    {
        try {
            $user = $this->userService->updateUserRoleAndStatus($id, $request->validated(), auth()->user());
            return back()->with('success', "Akun {$user->name} berhasil diperbarui.");
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return back()->with('error', $errors['error'][0] ?? 'Gagal memperbarui user.');
        }
    }

    /**
     * Show the edit form for the specified book.
     */
    public function editBook($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Akses Ditolak');
        }

        $book = $this->ebookService->findBook($id);
        $categories = $this->categoryService->getAllCategories();
        $courses = $this->courseService->getAllCourses();

        return view('admin.books_edit', compact('book', 'categories', 'courses'));
    }

    /**
     * Process changes to the specified book.
     */
    public function updateBook(UpdateBookRequest $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Akses Ditolak');
        }

        $this->ebookService->updateEbook(
            $id,
            $request->validated(),
            $request->file('file'),
            $request->file('cover')
        );

        return redirect()->route('admin.books')->with('success', 'Buku berhasil diperbarui!');
    }
}
