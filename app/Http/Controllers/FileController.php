<?php
namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileCategory;
use App\Models\Department;
use App\Models\FileShare;
use App\Mail\FileShared;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // list with filters, owner sees files, admin sees all, others see accessible
    public function index(Request $request)
    {
        $categories = FileCategory::all();
        $departments = Department::all();
        $user = auth()->user();

        if($user->hasRole('admin')){
            $query = File::with('category','department','uploader')->latest();
        } else {
            // files accessible by the user (owned, same dept, shared)
            $query = File::with('category','department','uploader')
                ->where(function($q) use ($user) {
                    $q->where('uploaded_by', $user->id)
                      ->orWhere('department_id', $user->department_id)
                      ->orWhereHas('shares', function($sq) use ($user) {
                          $sq->where('email', $user->email);
                      });
                })->latest();
        }

        if($request->filled('category_id')) $query->where('category_id',$request->category_id);
        if($request->filled('department_id')) $query->where('department_id',$request->department_id);
        if($request->filled('search')) $query->where('original_name','like','%'.$request->search.'%');

        $files = $query->paginate(20);

        return view('files.index', compact('files','categories','departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'=>'required|file|max:51200', // 50MB
            'category_id'=>'nullable|exists:file_categories,id',
            'department_id'=>'nullable|exists:departments,id',
        ]);

        $uploaded = $request->file('file');
        $origName = $uploaded->getClientOriginalName();
        $name = pathinfo($origName, PATHINFO_FILENAME) . '_' . Str::random(6) . '.' . $uploaded->getClientOriginalExtension();
        $path = $uploaded->storeAs('uploads', $name, 'public');

        $file = File::create([
            'original_name'=>$origName,
            'name'=>$name,
            'path'=>$path,
            'mime_type'=>$uploaded->getClientMimeType(),
            'category_id'=>$request->category_id,
            'department_id'=>$request->department_id,
            'uploaded_by'=>auth()->id(),
        ]);

        return back()->with('success','File uploaded successfully');
    }

    public function view(File $file)
    {
        $this->authorize('view', $file);
        $full = storage_path('app/public/'.$file->path);
        if(!file_exists($full)) abort(404,'File not found');
        return response()->file($full);
    }

    public function download(File $file)
    {
        $this->authorize('download', $file);
        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);
        $file->delete();
        return back()->with('success','File moved to trash');
    }

    public function trash()
    {
        $this->authorize('viewTrash', File::class);
        $files = File::onlyTrashed()->with('uploader')->paginate(20);
        return view('files.trash', compact('files'));
    }

    public function restore($id)
    {
        $file = File::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $file);
        $file->restore();
        return back()->with('success','File restored');
    }

    public function forceDelete($id)
    {
        $file = File::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $file);
        Storage::disk('public')->delete($file->path);
        $file->forceDelete();
        return back()->with('success','File permanently deleted');
    }

    // share by email: record share and send email
    public function share(Request $request, File $file)
    {
        $this->authorize('share', $file);
        $request->validate([
            'email'=>'required|email',
            'access'=>'nullable|in:view,edit,sign',
            'expires_hours'=>'nullable|integer|min:1|max:168'
        ]);

        FileShare::create([
            'file_id'=>$file->id,
            'email'=>$request->email,
            'access'=>$request->access ?? 'view',
            'shared_by'=>auth()->id(),
        ]);

        // optionally set a share token for public link (not required)
        $token = Str::random(40);
        $file->update([
            'share_token'=>$token,
            'shared_until'=> $request->filled('expires_hours') ? now()->addHours($request->expires_hours) : null
        ]);

        // send Mailable
        $link = route('files.shared', ['token'=>$token]);
        Mail::to($request->email)->send(new FileShared($file, $link, auth()->user()));

        return back()->with('success','File shared via email');
    }

    // public shared link (token)
    public function shared($token)
    {
        $file = File::where('share_token',$token)->firstOrFail();
        if($file->isShareExpired()) abort(403,'Share link expired');
        $full = storage_path('app/public/'.$file->path);
        if(!file_exists($full)) abort(404);
        return response()->file($full);
    }
}
