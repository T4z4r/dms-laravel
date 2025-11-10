<?php
namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileCategory;
use App\Models\Department;
use App\Models\FileShare;
use App\Models\FileComment;
use App\Models\FileAccessRequest;
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
            // files accessible by the user (owned, shared, or explicitly allowed)
            // Department access is now controlled by allowed_users and restricted_departments
            $query = File::with('category','department','uploader')
                ->where(function($q) use ($user) {
                    $q->where('uploaded_by', $user->id)
                      ->orWhereHas('shares', function($sq) use ($user) {
                          $sq->where('email', $user->email);
                      })
                      ->orWhereJsonContains('allowed_users', $user->id);
                })
                ->where(function($q) use ($user) {
                    $q->whereNull('restricted_departments')
                      ->orWhereJsonDoesntContain('restricted_departments', $user->department_id);
                })
                ->latest();
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
            'custom_name'=>'nullable|string|max:255',
            'category_id'=>'nullable|exists:file_categories,id',
            'department_id'=>'nullable|exists:departments,id',
            'default_access'=>'nullable|in:view,comment,edit,sign',
        ]);

        $uploaded = $request->file('file');
        $origName = $uploaded->getClientOriginalName();

        // Use custom name if provided, otherwise use original name
        $displayName = $request->filled('custom_name') ? $request->custom_name : $origName;

        // Generate unique filename for storage
        $extension = $uploaded->getClientOriginalExtension();
        $name = pathinfo($displayName, PATHINFO_FILENAME) . '_' . Str::random(6) . '.' . $extension;
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

        // Store default access level in session for future sharing
        if($request->filled('default_access')) {
            session(['default_access_' . $file->id => $request->default_access]);
        }

        return back()->with('success','File uploaded successfully');
    }

    public function view(File $file)
    {
        $this->authorize('view', $file);
        $full = storage_path('app/public/'.$file->path);
        if(!file_exists($full)) abort(404,'File not found');
        return response()->file($full);
    }

    public function details(File $file)
    {
        $this->authorize('view', $file);

        try {
            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'size' => $file->getSize(),
                    'extension' => $file->getExtension(),
                    'icon' => $file->getIcon(),
                    'category' => $file->category ? $file->category->name : null,
                    'department' => $file->department ? $file->department->name : null,
                    'uploader' => $file->uploader ? $file->uploader->name : 'System',
                    'upload_date' => $file->created_at->format('M d, Y H:i'),
                    'modified_date' => $file->updated_at->format('M d, Y H:i'),
                    'description' => $file->mime_type . ' file',
                    'path' => $file->path,
                    'is_signed' => $file->is_signed,
                    'can_sign' => auth()->user()->can('sign', $file),
                    'can_download' => $file->canUserDownload(auth()->user()),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading file details: ' . $e->getMessage()
            ], 500);
        }
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

    // edit file metadata
    public function edit(File $file)
    {
        $this->authorize('edit', $file);
        $categories = FileCategory::all();
        $departments = Department::all();
        return view('files.edit', compact('file', 'categories', 'departments'));
    }

    // update file metadata
    public function update(Request $request, File $file)
    {
        $this->authorize('edit', $file);

        $request->validate([
            'custom_name'=>'nullable|string|max:255',
            'category_id'=>'nullable|exists:file_categories,id',
            'department_id'=>'nullable|exists:departments,id',
            'allowed_users'=>'nullable|array',
            'allowed_users.*'=>'exists:users,id',
            'restricted_departments'=>'nullable|array',
            'restricted_departments.*'=>'exists:departments,id',
            'access_type'=>'required|in:view_only,downloadable',
        ]);

        $file->update([
            'name' => $request->filled('custom_name') ? $request->custom_name : $file->name,
            'category_id' => $request->category_id,
            'department_id' => $request->department_id,
            'allowed_users' => $request->allowed_users,
            'restricted_departments' => $request->restricted_departments,
            'access_type' => $request->access_type,
        ]);

        return redirect()->route('files.index')->with('success', 'File updated successfully');
    }

    // add comment to file
    public function comment(Request $request, File $file)
    {
        $this->authorize('comment', $file);

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        FileComment::create([
            'file_id' => $file->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Comment added successfully');
    }

    // get comments for file (AJAX)
    public function getComments(File $file)
    {
        $this->authorize('view', $file);
        $comments = $file->comments()->with('user')->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at->format('M d, Y H:i'),
                ];
            })
        ]);
    }

    // get shares for file (AJAX)
    public function getShares(File $file)
    {
        $this->authorize('share', $file);
        $shares = $file->shares()->with('sharedBy')->get();

        return response()->json([
            'success' => true,
            'shares' => $shares->map(function($share) {
                return [
                    'id' => $share->id,
                    'email' => $share->email,
                    'access' => $share->access,
                    'created_at' => $share->created_at->diffForHumans(),
                ];
            })
        ]);
    }

    // generate shareable link
    public function generateShareableLink(File $file)
    {
        $this->authorize('share', $file);

        // Generate or get existing token
        $token = $file->share_token ?: Str::random(40);
        $file->update(['share_token' => $token]);

        $link = route('files.shared', ['token' => $token]);

        return response()->json([
            'success' => true,
            'link' => $link
        ]);
    }

    // update share permission
    public function updateShare(Request $request, $shareId)
    {
        $request->validate([
            'access' => 'required|in:view,comment,edit,sign'
        ]);

        $share = FileShare::findOrFail($shareId);
        $this->authorize('share', $share->file);

        $share->update(['access' => $request->access]);

        return response()->json([
            'success' => true,
            'message' => 'Share permission updated successfully'
        ]);
    }

    // remove share
    public function removeShare($shareId)
    {
        $share = FileShare::findOrFail($shareId);
        $this->authorize('share', $share->file);

        $share->delete();

        return response()->json([
            'success' => true,
            'message' => 'Share removed successfully'
        ]);
    }

    // request access to a file
    public function requestAccess(Request $request, File $file)
    {
        $request->validate([
            'requested_access' => 'required|in:view,comment,edit,sign',
            'request_message' => 'nullable|string|max:1000'
        ]);

        // Check if user already has access
        if ($file->isAccessibleBy(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'You already have access to this file'
            ], 400);
        }

        // Check if user already has a pending request
        $existingRequest = FileAccessRequest::where('file_id', $file->id)
            ->where('requester_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending access request for this file'
            ], 400);
        }

        FileAccessRequest::create([
            'file_id' => $file->id,
            'requester_id' => auth()->id(),
            'requested_access' => $request->requested_access,
            'request_message' => $request->request_message
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Access request sent successfully'
        ]);
    }

    // list access requests (for approvers)
    public function accessRequests()
    {
        $user = auth()->user();

        // Get requests for files the user can approve (owner, admin, or department head)
        $requests = FileAccessRequest::with(['file', 'requester'])
            ->whereHas('file', function($query) use ($user) {
                $query->where('uploaded_by', $user->id)
                      ->orWhere('department_id', $user->department_id)
                      ->orWhere(function($q) use ($user) {
                          $q->where('department_id', $user->department_id)
                            ->whereHas('department.users', function($uq) use ($user) {
                                $uq->where('id', $user->id)->where('role', 'Department Head');
                            });
                      });
            })
            ->orWhere(function($query) use ($user) {
                if ($user->hasRole('admin')) {
                    $query->whereNotNull('id'); // Admin can see all
                }
            })
            ->pending()
            ->latest()
            ->paginate(20);

        return view('files.access-requests', compact('requests'));
    }

    // approve access request
    public function approveAccessRequest(Request $request, FileAccessRequest $accessRequest)
    {
        $this->authorize('share', $accessRequest->file);

        $accessRequest->approve(auth()->user(), $request->response_message);

        return response()->json([
            'success' => true,
            'message' => 'Access request approved successfully'
        ]);
    }

    // reject access request
    public function rejectAccessRequest(Request $request, FileAccessRequest $accessRequest)
    {
        $this->authorize('share', $accessRequest->file);

        $accessRequest->reject(auth()->user(), $request->response_message);

        return response()->json([
            'success' => true,
            'message' => 'Access request rejected'
        ]);
    }

    // get access request details (AJAX)
    public function getAccessRequest(FileAccessRequest $accessRequest)
    {
        $this->authorize('share', $accessRequest->file);

        return response()->json([
            'success' => true,
            'request' => [
                'id' => $accessRequest->id,
                'file' => [
                    'original_name' => $accessRequest->file->original_name,
                    'size' => $accessRequest->file->getSize(),
                    'extension' => $accessRequest->file->getExtension()
                ],
                'requester' => [
                    'name' => $accessRequest->requester->name,
                    'email' => $accessRequest->requester->email
                ],
                'requested_access' => $accessRequest->requested_access,
                'request_message' => $accessRequest->request_message,
                'created_at_formatted' => $accessRequest->created_at->format('M d, Y \a\t H:i')
            ]
        ]);
    }
}
