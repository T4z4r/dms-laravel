@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-file-alt me-2"></i>File Management System
                </h2>
                <p class="text-muted mb-0">Organize, manage, and access your documents efficiently</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('files.trash') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-trash me-2"></i>Trash ({{ $files->total() }})
                </a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-2"></i>Upload File
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $files->total() }}</h4>
                        <small>Total Files</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-folder fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $categories->count() }}</h4>
                        <small>Categories</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-building fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $departments->count() }}</h4>
                        <small>Departments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $files->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                        <small>This Week</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Table -->
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Files
                    </h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if (session('success'))
                    <div class="alert alert-success m-3 mb-0">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">
                                    <i class="fas fa-file me-2"></i>File Details
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-user me-2"></i>Uploaded By
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-folder me-2"></i>Category
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-building me-2"></i>Department
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-calendar me-2"></i>Date
                                </th>
                                <th class="border-0 text-center">
                                    <i class="fas fa-cogs me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($files as $index => $file)
                                <tr>
                                    <td class="text-muted">{{ $files->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas fa-{{ $file->getIcon() }} fa-2x text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $file->original_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-hdd me-1"></i>{{ $file->getSize() }}
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-file-code me-1"></i>{{ strtoupper($file->getExtension()) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ substr($file->uploader->name ?? 'S', 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="fw-medium">{{ $file->uploader->name ?? 'System' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($file->category)
                                            <span class="badge bg-primary">{{ $file->category->name }}</span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($file->department)
                                            <span class="badge bg-info">{{ $file->department->name }}</span>
                                        @else
                                            <span class="text-muted">No Department</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted d-block">{{ $file->created_at->format('M d, Y') }}</small>
                                            <small class="text-muted">{{ $file->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="viewFile({{ $file->id }})" title="View File">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($file->canUserEdit(auth()->user()))
                                                <a href="{{ route('files.edit', $file) }}" class="btn btn-sm btn-outline-info mx-1" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            @if($file->is_signed)
                                                <a href="{{ route('files.signature.view', $file) }}" class="btn btn-sm btn-outline-warning mx-1" title="View Signature">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            @if($file->canUserDownload(auth()->user()))
                                                <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success mx-1" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            @if($file->canUserComment(auth()->user()))
                                                <button class="btn btn-sm btn-outline-secondary mx-1" data-bs-toggle="modal" data-bs-target="#commentModal" onclick="openCommentModal({{ $file->id }}, '{{ $file->original_name }}')" title="Comment">
                                                    <i class="fas fa-comment"></i>
                                                </button>
                                            @endif
                                            @can('share', $file)
                                                <button class="btn btn-sm btn-outline-info mx-1" data-bs-toggle="modal" data-bs-target="#shareModal" onclick="openShareModal({{ $file->id }}, '{{ $file->original_name }}')" title="Share">
                                                    <i class="fas fa-share"></i>
                                                </button>
                                            @endcan
                                            @unless($file->isAccessibleBy(auth()->user()))
                                                <button class="btn btn-sm btn-outline-warning mx-1" data-bs-toggle="modal" data-bs-target="#requestAccessModal" onclick="openRequestAccessModal({{ $file->id }}, '{{ $file->original_name }}')" title="Request Access">
                                                    <i class="fas fa-hand-paper"></i>
                                                </button>
                                            @endunless
                                            @if($file->canUserEdit(auth()->user()))
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteFile({{ $file->id }}, '{{ $file->original_name }}')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                                            <h5>No files uploaded yet</h5>
                                            <p>Start by uploading your first file using the button above.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($files->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $files->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Upload File</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="custom_name" class="form-label">File Name (Optional)</label>
                        <input type="text" name="custom_name" id="custom_name" class="form-control" placeholder="Enter custom file name or leave blank to use original name">
                        <div class="form-text">Leave blank to use the original file name</div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Select Category (Optional)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">Select Department (Optional)</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $department->id == auth()->user()->department_id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="default_access" class="form-label">Default Access Level</label>
                        <select name="default_access" id="default_access" class="form-select">
                            <option value="view">View Only</option>
                            <option value="comment">Comment</option>
                            <option value="edit">Edit</option>
                            <option value="sign">Sign</option>
                        </select>
                        <div class="form-text">This sets the default access level for users who will be granted access to this file</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View File Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewModalLabel">
                        <i class="fas fa-file-alt me-2"></i>File Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 mb-0">Loading file details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="downloadBtn" class="btn btn-success" download>
                        <i class="fas fa-download me-2"></i>Download
                    </a>
                    <a href="#" id="signBtn" class="btn btn-info" style="display: none;" data-bs-toggle="modal" data-bs-target="#signModal">
                        <i class="fas fa-signature me-2"></i>Sign Document
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature Modal -->
    <div class="modal fade" id="signModal" tabindex="-1" aria-labelledby="signModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="signModalLabel">
                        <i class="fas fa-signature me-2"></i>Sign Document
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="signForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Signature Type</label>
                            <select name="signature_type" id="signature_type_modal" class="form-select" required>
                                <option value="drawn">Drawn Signature</option>
                                <option value="typed">Typed Signature</option>
                                <option value="uploaded">Uploaded Image</option>
                            </select>
                        </div>

                        <div id="drawn_signature_modal" class="mb-3" style="display: none;">
                            <label class="form-label">Draw Your Signature</label>
                            <canvas id="signatureCanvasModal" width="400" height="200" style="border: 1px solid #ccc;"></canvas>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearCanvasModal()">Clear</button>
                            </div>
                            <input type="hidden" name="signature_image" id="signature_image_drawn_modal">
                        </div>

                        <div id="typed_signature_modal" class="mb-3" style="display: none;">
                            <label class="form-label">Type Your Signature</label>
                            <textarea name="signature_image" class="form-control" rows="5" placeholder="Enter your typed signature here"></textarea>
                        </div>

                        <div id="uploaded_signature_modal" class="mb-3" style="display: none;">
                            <label class="form-label">Upload Signature Image</label>
                            <input type="file" name="signature_image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="signSubmitBtn">Sign Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="commentForm" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="commentModalLabel">
                        <i class="fas fa-comment me-2"></i>Add Comment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="comment_text" class="form-label">Comment</label>
                        <textarea name="comment" id="comment_text" class="form-control" rows="4" placeholder="Enter your comment..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add Comment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="shareModalLabel">
                        <i class="fas fa-share me-2"></i>Share File
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Share Form -->
                    <form id="shareForm" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="share_email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="share_email" class="form-control" placeholder="Enter email address" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="share_access" class="form-label">Access Level</label>
                                    <select name="access" id="share_access" class="form-select" required>
                                        <option value="view">Viewer</option>
                                        <option value="comment">Commenter</option>
                                        <option value="edit">Editor</option>
                                        <option value="sign">Signer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_hours" class="form-label">Expiration (Optional)</label>
                                    <select name="expires_hours" id="expires_hours" class="form-select">
                                        <option value="">Never expires</option>
                                        <option value="24">1 day</option>
                                        <option value="168">1 week</option>
                                        <option value="720">1 month</option>
                                        <option value="8760">1 year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-share me-2"></i>Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Current Shares -->
                    <div id="currentShares">
                        <h6 class="mb-3">
                            <i class="fas fa-users me-2"></i>People with access
                        </h6>
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Link Sharing -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3">
                            <i class="fas fa-link me-2"></i>Get shareable link
                        </h6>
                        <div class="input-group">
                            <input type="text" id="shareableLink" class="form-control" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyShareableLink()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="form-text">Anyone with this link can access the file</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Access Modal -->
    <div class="modal fade" id="requestAccessModal" tabindex="-1" aria-labelledby="requestAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="requestAccessForm" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="requestAccessModalLabel">
                        <i class="fas fa-hand-paper me-2"></i>Request Access
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="requested_access" class="form-label">Access Level Requested</label>
                        <select name="requested_access" id="requested_access" class="form-select" required>
                            <option value="view">View Only</option>
                            <option value="comment">Comment</option>
                            <option value="edit">Edit</option>
                            <option value="sign">Sign</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="request_message" class="form-label">Request Message (Optional)</label>
                        <textarea name="request_message" id="request_message" class="form-control" rows="3" placeholder="Explain why you need access to this file..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Send Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden form for delete -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function viewFile(fileId) {
            // Show loading state
            const modalBody = document.getElementById('viewModalBody');
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Loading file details...</p>
                </div>
            `;

            // Fetch file details via AJAX
            fetch(`/files/${fileId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const file = data.file;
                        const downloadBtn = document.getElementById('downloadBtn');
                        const signBtn = document.getElementById('signBtn');
                        downloadBtn.href = `/files/${fileId}/download`;

                        // Hide download button if user cannot download
                        if (!file.can_download) {
                            downloadBtn.style.display = 'none';
                        } else {
                            downloadBtn.style.display = 'inline-block';
                        }

                        if (file.can_sign && !file.is_signed) {
                            signBtn.style.display = 'inline-block';
                            signBtn.onclick = function() {
                                document.getElementById('signForm').action = `/files/${fileId}/sign`;
                                updateSignatureFieldsModal();
                                const signModal = new bootstrap.Modal(document.getElementById('signModal'));
                                signModal.show();
                            };
                        } else {
                            signBtn.style.display = 'none';
                        }

                        modalBody.innerHTML = `
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-${file.icon} fa-3x text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">${file.original_name}</h5>
                                            <p class="text-muted mb-0">${file.description || 'No description available'}</p>
                                            ${file.is_signed ? '<span class="badge bg-success">Signed</span>' : '<span class="badge bg-warning">Not Signed</span>'}
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="border-start border-primary border-4 ps-3">
                                                <small class="text-muted d-block">File Size</small>
                                                <strong>${file.size}</strong>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="border-start border-info border-4 ps-3">
                                                <small class="text-muted d-block">File Type</small>
                                                <strong>${file.extension.toUpperCase()}</strong>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="border-start border-success border-4 ps-3">
                                                <small class="text-muted d-block">Category</small>
                                                <strong>${file.category || 'Uncategorized'}</strong>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="border-start border-warning border-4 ps-3">
                                                <small class="text-muted d-block">Department</small>
                                                <strong>${file.department || 'No Department'}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-info-circle me-2"></i>Information
                                            </h6>
                                            <div class="mb-2">
                                                <small class="text-muted d-block">Uploaded by</small>
                                                <strong>${file.uploader}</strong>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted d-block">Upload Date</small>
                                                <strong>${file.upload_date}</strong>
                                            </div>
                                            <div class="mb-0">
                                                <small class="text-muted d-block">Last Modified</small>
                                                <strong>${file.modified_date}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        modalBody.innerHTML = `
                            <div class="text-center py-4 text-danger">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h5>Error loading file</h5>
                                <p>${data.message || 'Unable to load file details.'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    modalBody.innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <h5>Connection Error</h5>
                            <p>Unable to load file details. Please try again.</p>
                        </div>
                    `;
                });
        }

        function deleteFile(fileId, fileName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to delete "${fileName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/files/${fileId}`;
                    form.submit();
                }
            });
        }

        function openCommentModal(fileId, fileName) {
            document.getElementById('commentModalLabel').innerHTML = `<i class="fas fa-comment me-2"></i>Comment on "${fileName}"`;
            const form = document.getElementById('commentForm');
            form.action = `/files/${fileId}/comment`;
            document.getElementById('comment_text').value = '';
        }

        function openRequestAccessModal(fileId, fileName) {
            document.getElementById('requestAccessModalLabel').innerHTML = `<i class="fas fa-hand-paper me-2"></i>Request Access to "${fileName}"`;
            const form = document.getElementById('requestAccessForm');
            form.action = `/files/${fileId}/request-access`;
            document.getElementById('request_message').value = '';
        }

        function openShareModal(fileId, fileName) {
            document.getElementById('shareModalLabel').innerHTML = `<i class="fas fa-share me-2"></i>Share "${fileName}"`;
            const form = document.getElementById('shareForm');
            form.action = `/files/${fileId}/share`;

            // Load current shares
            loadCurrentShares(fileId);

            // Generate shareable link
            generateShareableLink(fileId);
        }

        function loadCurrentShares(fileId) {
            fetch(`/files/${fileId}/shares`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('currentShares');
                    if (data.success) {
                        let html = '<h6 class="mb-3"><i class="fas fa-users me-2"></i>People with access</h6>';
                        if (data.shares.length === 0) {
                            html += '<p class="text-muted mb-0">No one else has access yet</p>';
                        } else {
                            data.shares.forEach(share => {
                                const accessBadge = getAccessBadge(share.access);
                                const accessOptions = getAccessOptions(share.access);
                                html += `
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                        <div>
                                            <strong>${share.email}</strong>
                                            <br><small class="text-muted">Shared ${share.created_at}</small>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <select class="form-select form-select-sm" style="width: auto;" onchange="changeSharePermission(${share.id}, this.value)">
                                                ${accessOptions}
                                            </select>
                                            <button class="btn btn-sm btn-outline-danger" onclick="removeShare(${share.id})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        container.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error loading shares:', error);
                });
        }

        function getAccessBadge(access) {
            const badges = {
                'view': '<span class="badge bg-secondary">Viewer</span>',
                'comment': '<span class="badge bg-info">Commenter</span>',
                'edit': '<span class="badge bg-warning">Editor</span>',
                'sign': '<span class="badge bg-success">Signer</span>'
            };
            return badges[access] || '<span class="badge bg-secondary">Unknown</span>';
        }

        function getAccessOptions(currentAccess) {
            const options = [
                { value: 'view', label: 'Viewer' },
                { value: 'comment', label: 'Commenter' },
                { value: 'edit', label: 'Editor' },
                { value: 'sign', label: 'Signer' }
            ];

            return options.map(option =>
                `<option value="${option.value}" ${option.value === currentAccess ? 'selected' : ''}>${option.label}</option>`
            ).join('');
        }

        function changeSharePermission(shareId, newAccess) {
            fetch(`/shares/${shareId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ access: newAccess })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload shares to show updated permissions
                    const fileId = document.getElementById('shareForm').action.split('/')[2];
                    loadCurrentShares(fileId);
                } else {
                    alert('Error updating permission: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating permission');
            });
        }

        function generateShareableLink(fileId) {
            fetch(`/files/${fileId}/generate-link`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('shareableLink').value = data.link;
                    }
                })
                .catch(error => {
                    console.error('Error generating link:', error);
                });
        }

        function copyShareableLink() {
            const linkInput = document.getElementById('shareableLink');
            linkInput.select();
            document.execCommand('copy');

            // Show feedback
            const btn = event.target.closest('button');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');

            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        }

        function removeShare(shareId) {
            if (confirm('Are you sure you want to remove this share?')) {
                fetch(`/shares/${shareId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload shares
                        const fileId = document.getElementById('shareForm').action.split('/')[2];
                        loadCurrentShares(fileId);
                    } else {
                        alert('Error removing share: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error removing share');
                });
            }
        }

        // Auto-refresh page after successful upload
        @if(session('success'))
            setTimeout(() => {
                location.reload();
            }, 2000);
        @endif

        // Signature modal functions
        const signatureTypeModal = document.getElementById('signature_type_modal');
        const drawnSignatureModal = document.getElementById('drawn_signature_modal');
        const typedSignatureModal = document.getElementById('typed_signature_modal');
        const uploadedSignatureModal = document.getElementById('uploaded_signature_modal');

        function updateSignatureFieldsModal() {
            drawnSignatureModal.style.display = 'none';
            typedSignatureModal.style.display = 'none';
            uploadedSignatureModal.style.display = 'none';

            const type = signatureTypeModal.value;
            if (type === 'drawn') {
                drawnSignatureModal.style.display = 'block';
                initCanvasModal();
            } else if (type === 'typed') {
                typedSignatureModal.style.display = 'block';
            } else if (type === 'uploaded') {
                uploadedSignatureModal.style.display = 'block';
            }
        }

        signatureTypeModal.addEventListener('change', updateSignatureFieldsModal);

        let canvasModal, ctxModal, isDrawingModal = false;

        function initCanvasModal() {
            canvasModal = document.getElementById('signatureCanvasModal');
            ctxModal = canvasModal.getContext('2d');
            ctxModal.strokeStyle = '#000';
            ctxModal.lineWidth = 2;
            ctxModal.lineCap = 'round';

            canvasModal.addEventListener('mousedown', startDrawingModal);
            canvasModal.addEventListener('mousemove', drawModal);
            canvasModal.addEventListener('mouseup', stopDrawingModal);
            canvasModal.addEventListener('mouseout', stopDrawingModal);

            // Touch events for mobile
            canvasModal.addEventListener('touchstart', startDrawingTouchModal);
            canvasModal.addEventListener('touchmove', drawTouchModal);
            canvasModal.addEventListener('touchend', stopDrawingModal);
        }

        function startDrawingModal(e) {
            isDrawingModal = true;
            ctxModal.beginPath();
            ctxModal.moveTo(e.offsetX, e.offsetY);
        }

        function drawModal(e) {
            if (!isDrawingModal) return;
            ctxModal.lineTo(e.offsetX, e.offsetY);
            ctxModal.stroke();
        }

        function stopDrawingModal() {
            isDrawingModal = false;
            updateSignatureImageModal();
        }

        function startDrawingTouchModal(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const rect = canvasModal.getBoundingClientRect();
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;
            isDrawingModal = true;
            ctxModal.beginPath();
            ctxModal.moveTo(x, y);
        }

        function drawTouchModal(e) {
            e.preventDefault();
            if (!isDrawingModal) return;
            const touch = e.touches[0];
            const rect = canvasModal.getBoundingClientRect();
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;
            ctxModal.lineTo(x, y);
            ctxModal.stroke();
        }

        function clearCanvasModal() {
            ctxModal.clearRect(0, 0, canvasModal.width, canvasModal.height);
            document.getElementById('signature_image_drawn_modal').value = '';
        }

        function updateSignatureImageModal() {
            const dataURL = canvasModal.toDataURL();
            document.getElementById('signature_image_drawn_modal').value = dataURL;
        }

        // Handle form submission
        document.getElementById('signForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('signSubmitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Signing...';
            submitBtn.disabled = true;

            const formData = new FormData(this);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Close modal and refresh page
                    const signModal = bootstrap.Modal.getInstance(document.getElementById('signModal'));
                    signModal.hide();
                    location.reload();
                } else {
                    alert('Error signing document: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error signing document. Please try again.');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>

@endsection
