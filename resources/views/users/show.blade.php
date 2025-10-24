@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">
                    <i class="fas fa-user me-2"></i>User Details
                </h2>
                <p class="text-muted mb-0">View user information.</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>User Information</h5>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Department:</strong> {{ $user->department->name ?? 'N/A' }}</p>
                <p><strong>Position:</strong> {{ $user->position ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                <p><strong>Status:</strong> <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span></p>
                <p><strong>Created At:</strong> {{ $user->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $user->updated_at->format('d M Y, H:i') }}</p>

                <h5>Associated Files</h5>
                @if($user->files->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">
                                        <i class="fas fa-file me-2"></i>File Details
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-folder me-2"></i>Category
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-calendar me-2"></i>Date
                                    </th>
                                    <th class="border-0 text-center">
                                        <i class="fas fa-cogs me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->files as $index => $file)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="fas fa-{{ $file->getIcon() }} fa-2x text-muted"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $file->original_name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-hdd me-1"></i>{{ $file->getSize() }}
                                                        <span class="mx-2">•</span>
                                                        <i class="fas fa-file-code me-1"></i>{{ strtoupper($file->getExtension()) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($file->category)
                                                <span class="badge bg-primary">{{ $file->category->name }}</span>
                                            @else
                                                <span class="text-muted">Uncategorized</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted d-block">{{ $file->created_at->format('M d, Y') }}</small>
                                                <small class="text-muted">{{ $file->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('files.view', $file) }}" class="btn btn-sm btn-outline-primary" title="View File">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success mx-1" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                            <h5>No files uploaded</h5>
                            <p>This user has not uploaded any files yet.</p>
                        </div>
                    </div>
                @endif

                <h5>Signatures</h5>
                @if($user->signatures->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">
                                        <i class="fas fa-file me-2"></i>File
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-signature me-2"></i>Type
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-calendar me-2"></i>Date
                                    </th>
                                    <th class="border-0 text-center">
                                        <i class="fas fa-cogs me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->signatures as $index => $signature)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('files.view', $signature->file) }}">{{ $signature->file->original_name }}</a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($signature->signature_type) }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted d-block">{{ $signature->created_at->format('M d, Y') }}</small>
                                                <small class="text-muted">{{ $signature->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('files.signature.view', $signature->file) }}" class="btn btn-sm btn-outline-primary" title="View Signature">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-signature fa-3x mb-3"></i>
                            <h5>No signatures</h5>
                            <p>This user has not signed any files yet.</p>
                        </div>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit User
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Delete this user?')">
                            <i class="fa fa-trash"></i> Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection