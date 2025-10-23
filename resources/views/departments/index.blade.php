@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">
                    <i class="fas fa-building me-2"></i>Departments
                </h2>
                <p class="text-muted mb-0">Manage departments here.</p>
            </div>
            <a href="{{ route('departments.create') }}" class="btn btn-main">
                <i class="fas fa-plus me-2"></i>Add Department
            </a>
        </div>

        <!-- Departments Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-striped align-middle">
                    <thead class="table-main text-white">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $index => $department)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $department->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this department?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
