<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shelly</title>
</head>
<body>
    <h1>🐚 Shelly</h1>
    <p>Upload a PDF, DOCX, TXT, or an image.</p>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('sets.store', [], false) }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".pdf,.docx,.txt,.jpg,.jpeg,.png,.webp" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>