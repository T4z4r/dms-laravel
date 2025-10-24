@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">
                    <i class="fas fa-user me-2"></i>My Profile
                </h2>
                <p class="text-muted mb-0">View your profile information.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>Profile Information</h5>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Department:</strong> {{ $user->department->name ?? 'N/A' }}</p>
                <p><strong>Position:</strong> {{ $user->position ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                <p><strong>Status:</strong> <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span></p>
                <p><strong>Created At:</strong> {{ $user->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $user->updated_at->format('d M Y, H:i') }}</p>

                @if($user->signature)
                    <h5>Signature</h5>
                    <img src="{{ asset($user->signature) }}" alt="Signature" style="max-width: 300px; max-height: 150px; border: 1px solid #ddd; padding: 10px;">
                @else
                    <h5>Signature</h5>
                    <p class="text-muted">No signature uploaded yet.</p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection