@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">
                    <i class="fas fa-trash me-2"></i>Trashed Files
                </h2>
                <p class="text-muted mb-0">Manage deleted files here.</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Files
            </a>
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
                            <th>Deleted At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $file->original_name }}</td>
                                <td>{{ $file->uploader->name ?? 'System' }}</td>
                                <td>{{ $file->deleted_at->format('d M Y, H:i') }}</td>
                                <td class="text-end">
                                    <form action="{{ route('files.restore', $file->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fa fa-undo"></i> Restore
                                        </button>
                                    </form>
                                    <form action="{{ route('files.forceDelete', $file->id) }}" method="POST" class="d-inline">
                                         @csrf @method('DELETE')
                                         <button type="submit" class="btn btn-sm btn-danger"
                                                 onclick="event.preventDefault(); confirmForceDelete('{{ $file->original_name }}', this.form);">
                                             <i class="fa fa-trash"></i> Delete
                                         </button>
                                     </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No trashed files.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $files->links() }}
            </div>
        </div>
    </div>

    <script>
        function confirmForceDelete(fileName, form) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Permanently delete "${fileName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
