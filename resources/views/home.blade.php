@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="mb-4">
            <h2 class="fw-bold text-main">Dashboard</h2>
            <p class="text-muted mb-0">Welcome to the Document Management System.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Files</h5>
                        <h3 class="text-main">{{ App\Models\File::count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Departments</h5>
                        <h3 class="text-main">{{ App\Models\Department::count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Categories</h5>
                        <h3 class="text-main">{{ App\Models\FileCategory::count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Signed Documents</h5>
                        <h3 class="text-main">{{ App\Models\Signature::count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Quick Actions</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('files.index') }}" class="btn btn-main">
                        <i class="fa fa-file"></i> Manage Files
                    </a>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                        <i class="fa fa-building"></i> Manage Departments
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fa fa-tags"></i> Manage Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
