@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">Edit Category</h2>
                <p class="text-muted mb-0">Update category information.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Categories
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" required>
                    </div>

                    <button type="submit" class="btn btn-main">Update Category</button>
                </form>
            </div>
        </div>
    </div>
@endsection