@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="mb-4">
            <h2 class="fw-bold text-main">Dashboard</h2>
            <p class="text-muted mb-0">Welcome back! Here's an overview of your Document Management System.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title">Total Files</h5>
                        <h3 class="text-main">{{ App\Models\File::count() }}</h3>
                        <small class="text-muted">Files uploaded</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-building fa-2x text-success"></i>
                        </div>
                        <h5 class="card-title">Departments</h5>
                        <h3 class="text-main">{{ App\Models\Department::count() }}</h3>
                        <small class="text-muted">Active departments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-tags fa-2x text-info"></i>
                        </div>
                        <h5 class="card-title">Categories</h5>
                        <h3 class="text-main">{{ App\Models\FileCategory::count() }}</h3>
                        <small class="text-muted">File categories</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-signature fa-2x text-warning"></i>
                        </div>
                        <h5 class="card-title">Signed Documents</h5>
                        <h3 class="text-main">{{ App\Models\Signature::count() }}</h3>
                        <small class="text-muted">Documents signed</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('files.index') }}" class="btn btn-primary">
                                <i class="fas fa-file-alt me-2"></i>Manage Files
                            </a>
                            <a href="{{ route('departments.index') }}" class="btn btn-success">
                                <i class="fas fa-building me-2"></i>Departments
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-info">
                                <i class="fas fa-tags me-2"></i>Categories
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Recent Activity
                        </h5>
                        <p class="text-muted">Recent file uploads and activities will appear here.</p>
                        <a href="{{ route('files.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>View All Files
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Files Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-clock me-2 text-primary"></i>Recent Files
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>File Name</th>
                                <th>Uploaded By</th>
                                <th>Department</th>
                                <th>Category</th>
                                <th>Uploaded At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentFiles = App\Models\File::with('uploader', 'department', 'category')->latest()->limit(5)->get();
                            @endphp
                            @forelse ($recentFiles as $file)
                                <tr>
                                    <td>{{ $file->original_name }}</td>
                                    <td>{{ $file->uploader->name ?? 'System' }}</td>
                                    <td>{{ $file->department->name ?? 'N/A' }}</td>
                                    <td>{{ $file->category->name ?? 'N/A' }}</td>
                                    <td>{{ $file->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('files.view', $file) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No files uploaded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('files.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>View All Files
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
