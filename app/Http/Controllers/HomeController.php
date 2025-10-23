<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Department;
use App\Models\FileCategory;
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
        // Files per department
        $filesPerDepartment = File::selectRaw('departments.name as department, COUNT(*) as count')
            ->join('departments', 'files.department_id', '=', 'departments.id')
            ->groupBy('departments.id', 'departments.name')
            ->get();

        // Files over time (last 12 months)
        $filesOverTime = File::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        // Files per category
        $filesPerCategory = FileCategory::selectRaw('file_categories.name as category, COUNT(files.id) as count')
            ->leftJoin('files', 'file_categories.id', '=', 'files.category_id')
            ->groupBy('file_categories.id', 'file_categories.name')
            ->get();

        return view('home', compact('filesPerDepartment', 'filesOverTime', 'filesPerCategory'));
    }
}
