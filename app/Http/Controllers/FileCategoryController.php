<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use Illuminate\Http\Request;

class FileCategoryController extends Controller
{
    public function index()
    {
        $categories = FileCategory::all();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:file_categories']);
        FileCategory::create($request->only('name'));
        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, FileCategory $category)
    {
        $request->validate(['name' => 'required|unique:file_categories,name,' . $category->id]);
        $category->update($request->only('name'));
        return back()->with('success', 'Category updated.');
    }

    public function destroy(FileCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
