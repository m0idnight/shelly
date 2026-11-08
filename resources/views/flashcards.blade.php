<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flashcards</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 0 auto; padding: 16px; text-align: center; }
        #scene { perspective: 1000px; height: 260px; margin: 20px 0; cursor: pointer; }
        #card { position: relative; width: 100%; height: 100%; transition: transform 0.6s; transform-style: preserve-3d; }
        #card.flipped { transform: rotateY(180deg); }
        .face { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
                padding: 20px; border: 2px solid #f9a8d4; border-radius: 16px; background: #fff0f6;
                backface-visibility: hidden; font-size: 20px; }
        .back { transform: rotateY(180deg); background: #fce7f3; }
        button { padding: 10px 18px; margin: 4px; font-size: 16px; }
    </style>
</head>
<body>
    <a href="/sets/{{ $set->id }}">← Back</a>
    <h1>🃏 {{ $set->title }}</h1>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/sets/{{ $set->id }}/flashcards/generate">
        @csrf
        <button type="submit">✨ {{ $set->flashcards->count() ? 'Re-make' : 'Make' }} flashcards</button>
    </form>

    @if ($set->flashcards->count())
        @php
            $cards = $set->flashcards->map(fn ($c) => ['front' => $c->front, 'back' => $c->back])->values();
        @endphp

        <p id="count"></p>
        <div id="scene" onclick="flip()">
            <div id="card">
                <div class="face" id="front"></div>
                <div class="face back" id="back"></div>
            </div>
        </div>
        <p>Tap the card to flip it 🦋</p>
        <button onclick="move(-1)">← Prev</button>
        <button onclick="move(1)">Next →</button>

        <script>
            const cards = @json($cards);
            let i = 0;

            function show() {
                document.getElementById('card').classList.remove('flipped');
                document.getElementById('front').textContent = cards[i].front;
                document.getElementById('back').textContent = cards[i].back;
                document.getElementById('count').textContent = (i + 1) + ' / ' + cards.length;
            }
            function flip() { document.getElementById('card').classList.toggle('flipped'); }
            function move(d) { i = (i + d + cards.length) % cards.length; show(); }
            show();
        </script>
    @endif
</body>
</html>