@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-white shadow-sm sidebar" style="min-height: 100vh;">
            <div class="sidebar-sticky pt-3">
                <div class="text-center mb-4">
                    <h5 class="text-main fw-bold">DMS</h5>
                    <small class="text-muted">Document Management</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active bg-primary text-white' : 'text-dark' }} d-flex align-items-center" href="{{ route('home') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('files.*') ? 'active bg-primary text-white' : 'text-dark' }} d-flex align-items-center" href="{{ route('files.index') }}">
                            <i class="fas fa-file-alt me-2"></i> Files
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('files.trash') ? 'active bg-primary text-white' : 'text-dark' }} d-flex align-items-center" href="{{ route('files.trash') }}">
                            <i class="fas fa-trash me-2"></i> Trash
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('departments.*') ? 'active bg-primary text-white' : 'text-dark' }} d-flex align-items-center" href="{{ route('departments.index') }}">
                            <i class="fas fa-building me-2"></i> Departments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active bg-primary text-white' : 'text-dark' }} d-flex align-items-center" href="{{ route('categories.index') }}">
                            <i class="fas fa-tags me-2"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-10 px-md-4 p-2">
            @yield('content')
        </main>
    </div>
</div>
@endsection
