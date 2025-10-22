<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>
    <p>{{ $sender->name ?? $sender->email }} has shared a file with you.</p>
    <p><strong>File:</strong> {{ $file->original_name }}</p>
    <p>Open it using this link:</p>
    <p><a href="{{ $link }}">{{ $link }}</a></p>
    <p>If you do not have access, please contact the sender.</p>
</body>
</html>
