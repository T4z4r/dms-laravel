@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">
                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                </h2>
                <p class="text-muted mb-0">Update your profile information.</p>
            </div>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Profile
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" name="position" id="position" class="form-control" value="{{ $user->position }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="signature" class="form-label">Signature</label>
                        <input type="file" name="signature" id="signature" class="form-control" accept="image/*">
                        <small class="text-muted">Upload an image file for your signature (JPEG, PNG, JPG, GIF, max 2MB).</small>
                        @if($user->signature)
                            <div class="mt-2">
                                <img src="{{ asset($user->signature) }}" alt="Current Signature" style="max-width: 200px; max-height: 100px;">
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-main">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
@endsection