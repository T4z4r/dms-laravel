@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-hand-paper me-2"></i>Access Requests
                </h2>
                <p class="text-muted mb-0">Manage file access requests from users</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Files
            </a>
        </div>

        <!-- Requests Table -->
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Pending Access Requests
                </h5>
            </div>
            <div class="card-body p-0">
                @if (session('success'))
                    <div class="alert alert-success m-3 mb-0">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">
                                    <i class="fas fa-file me-2"></i>File
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-user me-2"></i>Requester
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-key me-2"></i>Requested Access
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-calendar me-2"></i>Requested On
                                </th>
                                <th class="border-0">
                                    <i class="fas fa-cogs me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $index => $request)
                                <tr>
                                    <td class="text-muted">{{ $requests->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas fa-file fa-2x text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $request->file->original_name }}</h6>
                                                <small class="text-muted">{{ $request->file->getSize() }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ substr($request->requester->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="fw-medium">{{ $request->requester->name }}</span>
                                                <br><small class="text-muted">{{ $request->requester->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $accessLabels = [
                                                'view' => ['label' => 'Viewer', 'class' => 'bg-secondary'],
                                                'comment' => ['label' => 'Commenter', 'class' => 'bg-info'],
                                                'edit' => ['label' => 'Editor', 'class' => 'bg-warning'],
                                                'sign' => ['label' => 'Signer', 'class' => 'bg-success']
                                            ];
                                            $accessInfo = $accessLabels[$request->requested_access] ?? ['label' => 'Unknown', 'class' => 'bg-secondary'];
                                        @endphp
                                        <span class="badge {{ $accessInfo['class'] }}">{{ $accessInfo['label'] }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted d-block">{{ $request->created_at->format('M d, Y') }}</small>
                                            <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-success" onclick="approveRequest({{ $request->id }}, '{{ $request->file->original_name }}')" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="rejectRequest({{ $request->id }}, '{{ $request->file->original_name }}')" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewRequestDetails({{ $request->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-hand-paper fa-3x mb-3"></i>
                                            <h5>No pending access requests</h5>
                                            <p>All requests have been processed.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($requests->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="requestDetailsModalLabel">
                        <i class="fas fa-eye me-2"></i>Request Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="requestDetailsBody">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Request Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="approveForm" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">
                        <i class="fas fa-check me-2"></i>Approve Access Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="approveMessage"></p>
                    <div class="mb-3">
                        <label for="approve_response_message" class="form-label">Response Message (Optional)</label>
                        <textarea name="response_message" id="approve_response_message" class="form-control" rows="3" placeholder="Add a message to the requester..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Request Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="rejectForm" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times me-2"></i>Reject Access Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="rejectMessage"></p>
                    <div class="mb-3">
                        <label for="reject_response_message" class="form-label">Reason for Rejection</label>
                        <textarea name="response_message" id="reject_response_message" class="form-control" rows="3" placeholder="Explain why the request is being rejected..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function viewRequestDetails(requestId) {
            fetch(`/access-requests/${requestId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const request = data.request;
                        const modalBody = document.getElementById('requestDetailsBody');

                        modalBody.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>File Information</h6>
                                    <p><strong>File:</strong> ${request.file.original_name}</p>
                                    <p><strong>Size:</strong> ${request.file.size}</p>
                                    <p><strong>Type:</strong> ${request.file.extension.toUpperCase()}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Requester Information</h6>
                                    <p><strong>Name:</strong> ${request.requester.name}</p>
                                    <p><strong>Email:</strong> ${request.requester.email}</p>
                                    <p><strong>Requested Access:</strong> ${getAccessLabel(request.requested_access)}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6>Request Message</h6>
                                <p class="border p-2 rounded">${request.request_message || 'No message provided'}</p>
                            </div>
                            <div class="mt-3">
                                <h6>Request Timeline</h6>
                                <p><strong>Requested on:</strong> ${request.created_at_formatted}</p>
                            </div>
                        `;

                        const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Error loading request details:', error);
                });
        }

        function approveRequest(requestId, fileName) {
            document.getElementById('approveMessage').innerHTML = `Are you sure you want to approve access to "${fileName}"?`;
            const form = document.getElementById('approveForm');
            form.action = `/access-requests/${requestId}/approve`;
            document.getElementById('approve_response_message').value = '';

            const modal = new bootstrap.Modal(document.getElementById('approveModal'));
            modal.show();
        }

        function rejectRequest(requestId, fileName) {
            document.getElementById('rejectMessage').innerHTML = `Are you sure you want to reject access to "${fileName}"?`;
            const form = document.getElementById('rejectForm');
            form.action = `/access-requests/${requestId}/reject`;
            document.getElementById('reject_response_message').value = '';

            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            modal.show();
        }

        function getAccessLabel(access) {
            const labels = {
                'view': 'Viewer',
                'comment': 'Commenter',
                'edit': 'Editor',
                'sign': 'Signer'
            };
            return labels[access] || 'Unknown';
        }

        // Handle form submissions
        document.getElementById('approveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitRequestAction(this, 'approved');
        });

        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitRequestAction(this, 'rejected');
        });

        function submitRequestAction(form, action) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;

            const formData = new FormData(form);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal and refresh page
                    const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error processing request');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }
    </script>
@endsection