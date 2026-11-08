<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Flashcards | Shelly</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #e9a2bd;
            --pink-dark: #b9698b;
            --pink-light: #fff0f6;
            --pink-soft: #fff7fa;
            --cream: #fffdf9;
            --ink: #654b59;
            --muted: #a58d99;
            --line: #f3dce6;
            --white: #ffffff;
            --shadow: 0 8px 25px rgba(190, 117, 148, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 28px 18px 50px;
            font-family: 'Nunito', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 10%, #ffe4ef 0, transparent 25%),
                radial-gradient(circle at 90% 85%, #ffeaf2 0, transparent 25%),
                var(--cream);
        }

        button {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page {
            width: 100%;
            max-width: 760px;
            margin: auto;
        }

        /* Header */

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 35px;
        }

        .brand {
            font-family: 'Fredoka', sans-serif;
            font-size: 25px;
            font-weight: 600;
            color: var(--pink-dark);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: white;
            font-size: 13px;
            font-weight: 800;
            transition: .2s ease;
        }

        .back-link:hover {
            background: var(--pink-light);
            transform: translateY(-2px);
        }

        /* Heading */

        .heading {
            text-align: center;
            margin-bottom: 28px;
        }

        .eyebrow {
            display: inline-block;
            padding: 7px 15px;
            margin-bottom: 12px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: var(--pink-light);
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1.5px;
        }

        h1 {
            margin: 0;
            font-family: 'Fredoka', sans-serif;
            font-size: clamp(27px, 5vw, 38px);
            font-weight: 600;
            color: var(--pink-dark);
        }

        .subtitle {
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .decoration {
            margin-top: 12px;
            font-size: 19px;
            letter-spacing: 8px;
        }

        /* Main panel */

        .study-panel {
            position: relative;
            padding: 28px;
            border: 2px solid var(--line);
            border-radius: 27px;
            background: rgba(255, 255, 255, .88);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .study-panel::before {
            content: "🎀";
            position: absolute;
            top: 12px;
            right: 17px;
            font-size: 24px;
            opacity: .75;
        }

        .study-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .study-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .counter {
            padding: 8px 13px;
            border-radius: 30px;
            background: var(--pink-light);
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 900;
        }

        /* Progress */

        .progress-track {
            height: 8px;
            margin-bottom: 28px;
            border-radius: 30px;
            background: #f9e8ef;
            overflow: hidden;
        }

        .progress-fill {
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #f4bfd3, #d982a6);
            transition: width .3s ease;
        }

        /* Flashcard */

        #scene {
            height: 290px;
            perspective: 1200px;
            cursor: pointer;
            outline: none;
        }

        #card {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform .65s cubic-bezier(.2,.7,.3,1);
        }

        #card.flipped {
            transform: rotateY(180deg);
        }

        .face {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 2px solid #f4ccdc;
            border-radius: 24px;
            background:
                radial-gradient(circle at top right, #fff0f6, transparent 40%),
                #fffafd;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            text-align: center;
            overflow-wrap: anywhere;
            box-shadow: 0 7px 20px rgba(190, 117, 148, .08);
        }

        .face::before {
            content: "✿";
            position: absolute;
            top: 16px;
            left: 19px;
            color: #e9a2bd;
            font-size: 22px;
        }

        .face::after {
            content: "♡";
            position: absolute;
            right: 19px;
            bottom: 12px;
            color: #e9a2bd;
            font-size: 25px;
        }

        .back {
            transform: rotateY(180deg);
            background:
                radial-gradient(circle at bottom left, #ffe8f1, transparent 45%),
                #fff2f7;
        }

        .face-label {
            margin-bottom: 15px;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .face-content {
            max-width: 100%;
            font-family: 'Fredoka', sans-serif;
            font-size: clamp(19px, 4vw, 25px);
            line-height: 1.5;
            font-weight: 500;
        }

        .flip-hint {
            margin: 18px 0 22px;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        /* Buttons */

        .controls {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .nav-button {
            min-width: 125px;
            padding: 12px 20px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: white;
            color: var(--pink-dark);
            font-weight: 900;
            cursor: pointer;
            transition: .2s ease;
        }

        .nav-button:hover {
            background: var(--pink-light);
            transform: translateY(-2px);
        }

        .generate-button {
            display: block;
            margin: 24px auto 0;
            padding: 12px 22px;
            border: none;
            border-radius: 30px;
            background: var(--pink);
            color: white;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 4px 0 #d58eaa;
            transition: .2s ease;
        }

        .generate-button:hover {
            background: var(--pink-dark);
            transform: translateY(-2px);
        }

        /* Review status */

        .review-status {
            margin-top: 23px;
            padding: 14px;
            border: 1px dashed #eac0d2;
            border-radius: 16px;
            background: #fff8fb;
            text-align: center;
        }

        .review-status p {
            margin: 0;
            font-size: 13px;
            font-weight: 800;
            color: var(--pink-dark);
        }

        .review-status small {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 11px;
        }

        .error-message {
            margin-top: 12px;
            color: #bd5275;
            font-size: 12px;
            text-align: center;
        }

        /* Empty state */

        .empty-state {
            padding: 45px 20px;
            border: 2px dashed #efcada;
            border-radius: 23px;
            background: #fff8fb;
            text-align: center;
        }

        .empty-icon {
            font-size: 48px;
        }

        .empty-state h2 {
            margin: 12px 0 5px;
            font-family: 'Fredoka', sans-serif;
            color: var(--pink-dark);
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
        }

        .footer {
            margin-top: 28px;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 540px) {
            body {
                padding: 18px 13px 35px;
            }

            .topbar {
                margin-bottom: 28px;
            }

            .study-panel {
                padding: 20px 15px;
                border-radius: 22px;
            }

            #scene {
                height: 270px;
            }

            .face {
                padding: 20px;
            }

            .nav-button {
                min-width: 0;
                flex: 1;
                padding: 12px 10px;
            }

            .controls {
                gap: 8px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="brand">🎀 shelly</a>

        <a href="{{ route('sets.show', $set) }}" class="back-link">
            ← Back to study set
        </a>
    </header>

    <section class="heading">
        <span class="eyebrow">YOUR LITTLE STUDY CORNER</span>

        <h1>Flashcard Time! 🦋</h1>

        <p class="subtitle">
            {{ $set->title }}
        </p>

        <div class="decoration">♡ 🎀 ✿ 🦋 ♡</div>
    </section>

    @if ($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
    @endif

    <main class="study-panel">

        @if ($set->flashcards->count())

            @php
                $cards = $set->flashcards
                    ->map(fn ($c) => [
                        'front' => $c->front,
                        'back' => $c->back
                    ])
                    ->values();
            @endphp

            <div class="study-info">
                <span class="study-label">🌷 YOUR FLASHCARD COLLECTION</span>

                <span class="counter" id="count">
                    1 / {{ $cards->count() }}
                </span>
            </div>

            <div class="progress-track">
                <div class="progress-fill" id="progressFill"></div>
            </div>

            <div
                id="scene"
                role="button"
                tabindex="0"
                aria-label="Flip flashcard"
            >
                <div id="card">

                    <div class="face" id="front">
                        <span class="face-label">QUESTION ♡</span>
                        <span class="face-content" id="frontText"></span>
                    </div>

                    <div class="face back" id="back">
                        <span class="face-label">ANSWER ✿</span>
                        <span class="face-content" id="backText"></span>
                    </div>

                </div>
            </div>

            <p class="flip-hint">
                Tap the card to reveal the answer 🎀
            </p>

            <div class="controls">
                <button type="button" class="nav-button" id="prevButton">
                    ← Previous
                </button>

                <button type="button" class="nav-button" id="nextButton">
                    Next →
                </button>
            </div>

            <div class="review-status">
                <p>
                    🩷 Reviewed:
                    <span id="reviewedCount">0</span>
                    / {{ $cards->count() }} cards
                </p>

                <small>
                    Every new card you review earns 2 Shelly points!
                </small>
            </div>

            <p class="error-message" id="saveError" role="status" aria-live="polite"></p>

        @else

            <div class="empty-state">
                <div class="empty-icon">🧸</div>

                <h2>No flashcards yet!</h2>

                <p>
                    Let's make some cute study cards for this study set.
                </p>
            </div>

        @endif

        <form method="POST" action="{{ route('sets.flashcards.make', $set) }}">
            @csrf

            <button type="submit" class="generate-button">
                ✨ {{ $set->flashcards->count() ? 'Regenerate' : 'Create' }} Flashcards
            </button>
        </form>

    </main>

    <footer class="footer">
        Made with ♡ by Shelly
        <div style="margin-top: 8px;">🎀 🦋 🌷</div>
    </footer>

</div>

@if ($set->flashcards->count())
<script>
    const cards = @json($cards);
    const logUrl = @json(route('study-log.store'));
    const studySetId = @json($set->id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let currentIndex = 0;

    // Tracks cards successfully logged during this page session.
    const loggedCards = new Set();

    // Prevents duplicate requests while a card is being saved.
    const pendingCards = new Set();

    const cardElement = document.getElementById('card');
    const scene = document.getElementById('scene');

    function updateProgress() {
        document.getElementById('reviewedCount').textContent =
            loggedCards.size;

        const percentage = (loggedCards.size / cards.length) * 100;

        document.getElementById('progressFill').style.width =
            percentage + '%';
    }

    async function logReview(index) {
        if (loggedCards.has(index) || pendingCards.has(index)) {
            return;
        }

        pendingCards.add(index);

        const errorMessage = document.getElementById('saveError');
        errorMessage.textContent = '';

        try {
            const response = await fetch(logUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    study_set_id: studySetId,
                    type: 'flashcard'
                })
            });

            if (!response.ok) {
                throw new Error('Progress could not be saved.');
            }

            loggedCards.add(index);
            updateProgress();

        } catch (error) {
            console.error('Flashcard progress error:', error);

            errorMessage.textContent =
                'Your review could not be saved. Please try again.';

        } finally {
            pendingCards.delete(index);
        }
    }

    function showCard() {
        cardElement.classList.remove('flipped');

        document.getElementById('frontText').textContent =
            cards[currentIndex].front;

        document.getElementById('backText').textContent =
            cards[currentIndex].back;

        document.getElementById('count').textContent =
            (currentIndex + 1) + ' / ' + cards.length;

        document.getElementById('prevButton').disabled =
            cards.length <= 1;

        document.getElementById('nextButton').disabled =
            cards.length <= 1;
    }

    function flipCard() {
        const wasFlipped = cardElement.classList.contains('flipped');

        cardElement.classList.toggle('flipped');

        // Only count the first reveal of this card.
        if (!wasFlipped) {
            logReview(currentIndex);
        }
    }

    function moveCard(direction) {
        currentIndex =
            (currentIndex + direction + cards.length) % cards.length;

        showCard();
    }

    scene.addEventListener('click', flipCard);

    scene.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            flipCard();
        }
    });

    document.getElementById('prevButton').addEventListener('click', function() {
        moveCard(-1);
    });

    document.getElementById('nextButton').addEventListener('click', function() {
        moveCard(1);
    });

    showCard();
    updateProgress();
</script>
@endif

</body>
</html>
