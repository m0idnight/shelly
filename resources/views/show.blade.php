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

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('sets.summarize', $set, false) }}">
        @csrf
        <button type="submit">✨ {{ $set->summary ? 'Re-make' : 'Make' }} summary</button>
    </form>
    <p><a href="/sets/{{ $set->id }}/flashcards">🃏 Flashcards</a></p>
    @if ($set->summary)
        <h2>Summary</h2>
        <div>{!! Str::markdown($set->summary, ['html_input' => 'escape']) !!}</div>
    @endif

    <h2>Original</h2>
    @if ($set->image_path)
        <img src="/storage/{{ $set->image_path }}" style="max-width:100%">
    @else
        <pre style="white-space: pre-wrap">{{ $set->source_text }}</pre>
    @endif
</body>
</html>