<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use App\Models\Department;
use App\Models\FileCategory;
use App\Models\Signature;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Files per department
        $filesPerDepartmentQuery = File::selectRaw('departments.name as department, COUNT(*) as count')
            ->join('departments', 'files.department_id', '=', 'departments.id');

        if (!$user->hasRole('admin')) {
            $filesPerDepartmentQuery->where(function($q) use ($user) {
                $q->where('files.uploaded_by', $user->id)
                  ->orWhereExists(function($sub) use ($user) {
                      $sub->selectRaw(1)
                          ->from('file_shares')
                          ->whereColumn('file_shares.file_id', 'files.id')
                          ->where('file_shares.email', $user->email);
                  })
                  ->orWhereJsonContains('files.allowed_users', $user->id);
            })->where(function($q) use ($user) {
                $q->whereNull('files.restricted_departments')
                  ->orWhereJsonDoesntContain('files.restricted_departments', $user->department_id);
            });
        }

        $filesPerDepartment = $filesPerDepartmentQuery->groupBy('departments.id', 'departments.name')->get();

        // Files over time (last 12 months)
        $filesOverTimeQuery = File::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count');

        if (!$user->hasRole('admin')) {
            $filesOverTimeQuery->where(function($q) use ($user) {
                $q->where('uploaded_by', $user->id)
                  ->orWhereExists(function($sub) use ($user) {
                      $sub->selectRaw(1)
                          ->from('file_shares')
                          ->whereColumn('file_shares.file_id', 'files.id')
                          ->where('file_shares.email', $user->email);
                  })
                  ->orWhereJsonContains('allowed_users', $user->id);
            })->where(function($q) use ($user) {
                $q->whereNull('restricted_departments')
                  ->orWhereJsonDoesntContain('restricted_departments', $user->department_id);
            });
        }

        $filesOverTime = $filesOverTimeQuery->groupBy('month')->orderBy('month')->limit(12)->get();

        // Files per category
        $filesPerCategoryQuery = FileCategory::selectRaw('file_categories.name as category, COUNT(files.id) as count')
            ->leftJoin('files', 'file_categories.id', '=', 'files.category_id');

        if (!$user->hasRole('admin')) {
            $filesPerCategoryQuery->where(function($q) use ($user) {
                $q->where('files.uploaded_by', $user->id)
                  ->orWhereExists(function($sub) use ($user) {
                      $sub->selectRaw(1)
                          ->from('file_shares')
                          ->whereColumn('file_shares.file_id', 'files.id')
                          ->where('file_shares.email', $user->email);
                  })
                  ->orWhereJsonContains('files.allowed_users', $user->id);
            })->where(function($q) use ($user) {
                $q->whereNull('files.restricted_departments')
                  ->orWhereJsonDoesntContain('files.restricted_departments', $user->department_id);
            });
        }

        $filesPerCategory = $filesPerCategoryQuery->groupBy('file_categories.id', 'file_categories.name')->get();

        // Recent files with access control
        $recentFilesQuery = File::with('uploader', 'department', 'category')->latest();

        if (!$user->hasRole('admin')) {
            $recentFilesQuery->where(function($q) use ($user) {
                $q->where('uploaded_by', $user->id)
                  ->orWhereExists(function($sub) use ($user) {
                      $sub->selectRaw(1)
                          ->from('file_shares')
                          ->whereColumn('file_shares.file_id', 'files.id')
                          ->where('file_shares.email', $user->email);
                  })
                  ->orWhereJsonContains('allowed_users', $user->id);
            })->where(function($q) use ($user) {
                $q->whereNull('restricted_departments')
                  ->orWhereJsonDoesntContain('restricted_departments', $user->department_id);
            });
        }

        $recentFiles = $recentFilesQuery->limit(5)->get();

        // Statistics with access control
        $totalFiles = $user->hasRole('admin') ? File::count() : File::where(function($q) use ($user) {
            $q->where('uploaded_by', $user->id)
              ->orWhereExists(function($sub) use ($user) {
                  $sub->selectRaw(1)
                      ->from('file_shares')
                      ->whereColumn('file_shares.file_id', 'files.id')
                      ->where('file_shares.email', $user->email);
              })
              ->orWhereJsonContains('allowed_users', $user->id);
        })->where(function($q) use ($user) {
            $q->whereNull('restricted_departments')
              ->orWhereJsonDoesntContain('restricted_departments', $user->department_id);
        })->count();

        $totalDepartments = Department::count();
        $totalCategories = FileCategory::count();
        $totalSignatures = Signature::count();

        return view('home', compact('filesPerDepartment', 'filesOverTime', 'filesPerCategory', 'recentFiles', 'totalFiles', 'totalDepartments', 'totalCategories', 'totalSignatures'));
    }
}
