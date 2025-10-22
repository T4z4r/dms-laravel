@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">File Management</h2>
                <p class="text-muted mb-0">Manage your uploaded files here.</p>
            </div>
            <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fa fa-upload"></i> Upload File
            </button>
        </div>

        <!-- File Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-striped align-middle">
                    <thead class="table-main text-white">
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $file->original_name }}</td>
                                <td>{{ $file->user->name ?? 'System' }}</td>
                                <td>{{ $file->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <form action="{{ route('files.destroy', $file) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this file?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No files uploaded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header bg-main text-white">
                    <h5 class="modal-title">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-main">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection
