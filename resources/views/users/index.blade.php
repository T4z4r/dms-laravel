@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">
                    <i class="fas fa-users me-2"></i>Users
                </h2>
                <p class="text-muted mb-0">Manage users here.</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add User
            </a>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department->name ?? 'N/A' }}</td>
                                <td>{{ $user->position ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="event.preventDefault(); confirmDeleteUser('{{ $user->name }}', this.form);">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteUser(userName, form) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete "${userName}" user?`,
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