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

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:file_categories']);
        FileCategory::create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function show(FileCategory $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(FileCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, FileCategory $category)
    {
        $request->validate(['name' => 'required|unique:file_categories,name,' . $category->id]);
        $category->update($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(FileCategory $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
