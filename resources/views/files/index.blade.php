@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main mb-1">
                    <i class="fas fa-file-alt me-2"></i>File Management System
                </h2>
                <p class="text-muted mb-0">Organize, manage, and access your documents efficiently</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('files.trash') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-trash me-2"></i>Trash ({{ $files->total() }})
                </a>
                <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-2"></i>Upload File
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $files->total() }}</h4>
                        <small>Total Files</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-folder fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $categories->count() }}</h4>
                        <small>Categories</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-building fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $departments->count() }}</h4>
                        <small>Departments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h4 class="mb-1">{{ $files->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                        <small>This Week</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-main">
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
                                            <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteFile({{ $file->id }}, '{{ $file->original_name }}')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                <div class="modal-header bg-main text-white">
                    <h5 class="modal-title">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-main">Upload</button>
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
                </div>
            </div>
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
                        downloadBtn.href = `/files/${fileId}/download`;

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
            if (confirm(`Are you sure you want to delete "${fileName}"? This action cannot be undone.`)) {
                const form = document.getElementById('deleteForm');
                form.action = `/files/${fileId}`;
                form.submit();
            }
        }

        // Auto-refresh page after successful upload
        @if(session('success'))
            setTimeout(() => {
                location.reload();
            }, 2000);
        @endif
    </script>

    <style>
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin: 0 1px;
        }

        .btn-group .btn:first-child {
            margin-left: 0;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }
    </style>
@endsection
