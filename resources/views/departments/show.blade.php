@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">
                    <i class="fas fa-building me-2"></i>Department Details
                </h2>
                <p class="text-muted mb-0">View department information.</p>
            </div>
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Departments
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>Department Information</h5>
                <p><strong>Name:</strong> {{ $department->name }}</p>
                <p><strong>Created At:</strong> {{ $department->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $department->updated_at->format('d M Y, H:i') }}</p>

                <h5>Associated Files</h5>
                @if($department->files->count() > 0)
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
                                        <i class="fas fa-calendar me-2"></i>Date
                                    </th>
                                    <th class="border-0 text-center">
                                        <i class="fas fa-cogs me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department->files as $index => $file)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
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
                                                <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success mx-1" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                            <h5>No files associated</h5>
                            <p>No files have been uploaded to this department yet.</p>
                        </div>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit Department
                    </a>
                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Delete this department?')">
                            <i class="fa fa-trash"></i> Delete Department
                        </button>
                    </form>
                </div>
            </div>
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
    </script>
@endsection