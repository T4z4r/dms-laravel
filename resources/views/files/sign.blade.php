@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-main">
                    <i class="fas fa-signature me-2"></i>Sign Document
                </h2>
                <p class="text-muted mb-0">Sign the file: {{ $file->original_name }}</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Files
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('files.sign.store', $file) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Signature Type</label>
                        <select name="signature_type" id="signature_type" class="form-select" required>
                            <option value="drawn">Drawn Signature</option>
                            <option value="typed">Typed Signature</option>
                            <option value="uploaded">Uploaded Image</option>
                        </select>
                    </div>

                    <div id="drawn_signature" class="mb-3" style="display: none;">
                        <label class="form-label">Draw Your Signature</label>
                        <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #ccc;"></canvas>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearCanvas()">Clear</button>
                        </div>
                        <input type="hidden" name="signature_image" id="signature_image_drawn">
                    </div>

                    <div id="typed_signature" class="mb-3" style="display: none;">
                        <label class="form-label">Type Your Signature</label>
                        <textarea name="signature_image" class="form-control" rows="5" placeholder="Enter your typed signature here"></textarea>
                    </div>

                    <div id="uploaded_signature" class="mb-3" style="display: none;">
                        <label class="form-label">Upload Signature Image</label>
                        <input type="file" name="signature_image" class="form-control" accept="image/*">
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

    <script>
        const signatureType = document.getElementById('signature_type');
        const drawnSignature = document.getElementById('drawn_signature');
        const typedSignature = document.getElementById('typed_signature');
        const uploadedSignature = document.getElementById('uploaded_signature');

        function updateSignatureFields() {
            drawnSignature.style.display = 'none';
            typedSignature.style.display = 'none';
            uploadedSignature.style.display = 'none';

            const type = signatureType.value;
            if (type === 'drawn') {
                drawnSignature.style.display = 'block';
                initCanvas();
            } else if (type === 'typed') {
                typedSignature.style.display = 'block';
            } else if (type === 'uploaded') {
                uploadedSignature.style.display = 'block';
            }
        }

        signatureType.addEventListener('change', updateSignatureFields);
        updateSignatureFields();

        let canvas, ctx, isDrawing = false;

        function initCanvas() {
            canvas = document.getElementById('signatureCanvas');
            ctx = canvas.getContext('2d');
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';

            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Touch events for mobile
            canvas.addEventListener('touchstart', startDrawingTouch);
            canvas.addEventListener('touchmove', drawTouch);
            canvas.addEventListener('touchend', stopDrawing);
        }

        function startDrawing(e) {
            isDrawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        }

        function draw(e) {
            if (!isDrawing) return;
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        }

        function stopDrawing() {
            isDrawing = false;
            updateSignatureImage();
        }

        function startDrawingTouch(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;
            isDrawing = true;
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function drawTouch(e) {
            e.preventDefault();
            if (!isDrawing) return;
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;
            ctx.lineTo(x, y);
            ctx.stroke();
        }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('signature_image_drawn').value = '';
        }

        function updateSignatureImage() {
            const dataURL = canvas.toDataURL();
            document.getElementById('signature_image_drawn').value = dataURL;
        }
    </script>
@endsection
