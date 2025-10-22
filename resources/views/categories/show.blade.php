@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">Category Details</h2>
                <p class="text-muted mb-0">View category information.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Categories
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5>Category Information</h5>
                <p><strong>Name:</strong> {{ $category->name }}</p>
                <p><strong>Created At:</strong> {{ $category->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $category->updated_at->format('d M Y, H:i') }}</p>

                <div class="mt-4">
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit Category
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Delete this category?')">
                            <i class="fa fa-trash"></i> Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection