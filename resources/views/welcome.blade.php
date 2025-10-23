@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Document Management System</h1>
                <p class="lead mb-4">Streamline your document workflow with our powerful, secure, and user-friendly DMS. Manage files, departments, and categories effortlessly.</p>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/007bff/ffffff?text=DMS+Illustration" alt="DMS Illustration" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold text-main">Why Choose Our DMS?</h2>
                <p class="lead text-muted">Discover the features that make our Document Management System stand out.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">File Management</h5>
                        <p class="card-text">Upload, organize, and share files securely with advanced access controls and version management.</p>
                        <a href="{{ route('files.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right me-2"></i>Manage Files
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-building fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Department Organization</h5>
                        <p class="card-text">Organize your team into departments for better collaboration and access management.</p>
                        <a href="{{ route('departments.index') }}" class="btn btn-success">
                            <i class="fas fa-arrow-right me-2"></i>Manage Departments
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-tags fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Smart Categorization</h5>
                        <p class="card-text">Categorize files for easy access and improved search functionality.</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-info">
                            <i class="fas fa-arrow-right me-2"></i>Manage Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-file fa-2x text-primary"></i>
                </div>
                <h3 class="text-main">{{ App\Models\File::count() }}</h3>
                <p class="text-muted">Total Files</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-building fa-2x text-success"></i>
                </div>
                <h3 class="text-main">{{ App\Models\Department::count() }}</h3>
                <p class="text-muted">Departments</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-tags fa-2x text-info"></i>
                </div>
                <h3 class="text-main">{{ App\Models\FileCategory::count() }}</h3>
                <p class="text-muted">Categories</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-signature fa-2x text-warning"></i>
                </div>
                <h3 class="text-main">{{ App\Models\Signature::count() }}</h3>
                <p class="text-muted">Signed Documents</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-3">Ready to Get Started?</h2>
        <p class="lead mb-4">Join thousands of users who trust our DMS for their document management needs.</p>
        @auth
            <a href="{{ route('home') }}" class="btn btn-main btn-lg">
                <i class="fas fa-rocket me-2"></i>Launch Dashboard
            </a>
        @else
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-user-plus me-2"></i>Sign Up Free
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        @endauth
    </div>
</section>
@endsection
