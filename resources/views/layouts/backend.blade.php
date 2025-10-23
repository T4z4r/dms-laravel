@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar" style="min-height: 100vh;">
            <div class="sidebar-sticky pt-3">
                <div class="text-center mb-4">
                    <i class="fas fa-file-alt fa-3x text-primary mb-2"></i>
                    <h5 class="fw-bold">DMS</h5>
                    <small>Document Management</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} d-flex align-items-center" href="{{ route('home') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('files.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('files.index') }}">
                            <i class="fas fa-file-alt me-2"></i> Files
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('files.trash') ? 'active' : '' }} d-flex align-items-center" href="{{ route('files.trash') }}">
                            <i class="fas fa-trash me-2"></i> Trash
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('departments.index') }}">
                            <i class="fas fa-building me-2"></i> Departments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('categories.index') }}">
                            <i class="fas fa-tags me-2"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); confirmLogout();">
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
