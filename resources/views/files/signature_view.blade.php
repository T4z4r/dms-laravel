@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">Signature Details</h2>
                <p class="text-muted mb-0">View signature for: {{ $file->original_name }}</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Files
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>File Information</h5>
                        <p><strong>Name:</strong> {{ $file->original_name }}</p>
                        <p><strong>Uploaded By:</strong> {{ $file->uploader->name ?? 'System' }}</p>
                        <p><strong>Uploaded At:</strong> {{ $file->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Signature Information</h5>
                        <p><strong>Signed By:</strong> {{ $signature->user->name }}</p>
                        <p><strong>Signature Type:</strong> {{ ucfirst($signature->signature_type) }}</p>
                        <p><strong>Signed At:</strong> {{ $signature->created_at->format('d M Y, H:i') }}</p>
                        @if($signature->notes)
                            <p><strong>Notes:</strong> {{ $signature->notes }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <h5>Signature</h5>
                    @if($signature->signature_type == 'drawn' || $signature->signature_type == 'uploaded')
                        <img src="{{ $signature->signature_image }}" alt="Signature" class="img-fluid" style="max-width: 300px;">
                    @else
                        <div class="border p-3" style="font-family: cursive; font-size: 24px;">
                            {{ $signature->signature_image }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
