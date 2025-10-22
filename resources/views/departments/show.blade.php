@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">Department Details</h2>
                <p class="text-muted mb-0">View department information.</p>
            </div>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Departments
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5>Department Information</h5>
                <p><strong>Name:</strong> {{ $department->name }}</p>
                <p><strong>Created At:</strong> {{ $department->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $department->updated_at->format('d M Y, H:i') }}</p>

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
@endsection