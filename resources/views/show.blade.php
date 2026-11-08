<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $set->title }}</title>
</head>
<body>
    <a href="{{ route('sets.create', [], false) }}">← Upload another</a>
    <h1>{{ $set->title }}</h1>

    @if ($set->image_path)
        <img src="/storage/{{ $set->image_path }}" style="max-width:100%">
    @else
        <pre style="white-space: pre-wrap">{{ $set->source_text }}</pre>
    @endif
</body>
</html>