@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-edit me-2"></i>Edit File
                </h2>
                <p class="text-muted mb-0">Update file metadata and settings</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Files
            </a>
        </div>

        <!-- Edit Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>File Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('files.update', $file) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="custom_name" class="form-label">File Name</label>
                                <input type="text" name="custom_name" id="custom_name" class="form-control"
                                       value="{{ $file->name }}" placeholder="Enter file name">
                                <div class="form-text">Original name: {{ $file->original_name }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select name="category_id" id="category_id" class="form-select">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $file->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="department_id" class="form-label">Department</label>
                                        <select name="department_id" id="department_id" class="form-select">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ $file->department_id == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update File
                                </button>
                                <a href="{{ route('files.index') }}" class="btn btn-light">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- File Info Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>File Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted d-block">Size</small>
                            <strong>{{ $file->getSize() }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Type</small>
                            <strong>{{ strtoupper($file->getExtension()) }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Uploaded By</small>
                            <strong>{{ $file->uploader->name ?? 'System' }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Upload Date</small>
                            <strong>{{ $file->created_at->format('M d, Y H:i') }}</strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block">Last Modified</small>
                            <strong>{{ $file->updated_at->format('M d, Y H:i') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('files.view', $file) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="fas fa-eye me-2"></i>View File
                            </a>
                            <a href="{{ route('files.download', $file) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download me-2"></i>Download
                            </a>
                            @if($file->is_signed)
                                <a href="{{ route('files.signature.view', $file) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-signature me-2"></i>View Signature
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
