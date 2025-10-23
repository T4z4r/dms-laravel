@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">
                    <i class="fas fa-edit me-2"></i>Edit Department
                </h2>
                <p class="text-muted mb-0">Update department information.</p>
            </div>
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Departments
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('departments.update', $department) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Department Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $department->name }}" required>
                    </div>

                    <button type="submit" class="btn btn-main">Update Department</button>
                </form>
            </div>
        </div>
    </div>
@endsection