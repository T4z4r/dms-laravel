@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">Sign Document</h2>
                <p class="text-muted mb-0">Sign the file: {{ $file->original_name }}</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Files
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('files.sign.store', $file) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Signature Type</label>
                        <select name="signature_type" class="form-select" required>
                            <option value="drawn">Drawn Signature</option>
                            <option value="typed">Typed Signature</option>
                            <option value="uploaded">Uploaded Image</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Signature</label>
                        <textarea name="signature_image" class="form-control" rows="5" placeholder="For drawn or typed, enter base64 or text here"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-main">Sign Document</button>
                </form>
            </div>
        </div>
    </div>
@endsection
