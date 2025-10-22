<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function index()
    {
        $files = File::with('user')->latest()->get();
        return view('files.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // 5MB limit
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        File::create([
            'name' => pathinfo($uploadedFile->hashName(), PATHINFO_FILENAME),
            'original_name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $uploadedFile->getMimeType(),
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    public function download(File $file)
    {
        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    public function destroy(File $file)
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }
}
