@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h1>Welcome to Document Management System</h1>
                </div>
                <div class="card-body text-center">
                    <p class="lead">Efficiently manage your files, departments, and categories with our comprehensive DMS.</p>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">File Management</h5>
                                    <p class="card-text">Upload, organize, and share files securely.</p>
                                    <a href="{{ route('files.index') }}" class="btn btn-primary">Manage Files</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h5 class="card-title">Departments</h5>
                                    <p class="card-text">Organize your team into departments.</p>
                                    <a href="{{ route('departments.index') }}" class="btn btn-success">Manage Departments</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h5 class="card-title">Categories</h5>
                                    <p class="card-text">Categorize files for easy access.</p>
                                    <a href="{{ route('categories.index') }}" class="btn btn-info">Manage Categories</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        @auth
                            <a href="{{ route('home') }}" class="btn btn-main">Go to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
