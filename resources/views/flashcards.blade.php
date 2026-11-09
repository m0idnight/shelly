
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Flashcards | Shelly</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #d987a5;
            --pink-dark: #b96f8e;
            --pink-light: #f9e6ed;
            --pink-pale: #fff5f7;
            --cream: #fffaf8;
            --ink: #503b3b;
            --muted: #a58c91;
            --line: #efd9df;
            --white: #fffdfd;
            --shadow: 0 12px 35px rgba(151, 95, 115, .07);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 34px 20px 55px;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            background:
                radial-gradient(circle at 5% 5%, #fcecf0, transparent 27%),
                radial-gradient(circle at 95% 90%, #fff0eb, transparent 25%),
                var(--cream);
        }

        button, a {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            cursor: pointer;
        }

        .page {
            width: 100%;
            max-width: 900px;
            margin: auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 52px;
        }

        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--ink);
        }

        .brand span {
            color: var(--pink);
        }

        .back-link {
            padding: 11px 17px;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: rgba(255,255,255,.65);
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .back-link:hover {
            background: var(--pink-light);
        }

        .heading {
            margin-bottom: 34px;
            text-align: center;
        }

        .eyebrow {
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2.5px;
        }

        h1 {
            margin: 12px 0 8px;
            font-family: 'Playfair Display', serif;
            font-size: clamp(32px, 5vw, 43px);
            font-weight: 700;
            letter-spacing: .5px;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
            font-family: 'Playfair Display', serif;
            font-size: 15px;
        }

        .ornament {
            margin-top: 17px;
            color: var(--pink);
            font-size: 17px;
            letter-spacing: 10px;
        }

        .study-panel {
            padding: 30px;
            border: 1px solid var(--line);
            border-radius: 7px;
            background: rgba(255,255,255,.76);
            box-shadow: var(--shadow);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 13px;
            margin-bottom: 28px;
        }

        .stat {
            padding: 17px 12px;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: #fffafb;
            text-align: center;
        }

        .stat-number {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 27px;
            font-weight: 700;
        }

        .stat-label {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .stat.known .stat-number {
            color: #8eaa91;
        }

        .stat.review .stat-number {
            color: var(--pink-dark);
        }

        .stat.total .stat-number {
            color: var(--ink);
        }

        .progress-heading {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
        }

        .progress-track {
            height: 7px;
            margin-bottom: 28px;
            border-radius: 20px;
            background: #f5e8eb;
            overflow: hidden;
        }

        .progress-fill {
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #e7afc1, #c97d9b);
            transition: width .35s ease;
        }

        .card-area {
            max-width: 650px;
            margin: auto;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 13px;
        }

        .card-label {
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.6px;
        }

        .counter {
            padding: 8px 13px;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: var(--pink-pale);
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 800;
        }

        #scene {
            height: 330px;
            perspective: 1300px;
            cursor: pointer;
            touch-action: pan-y;
            outline: none;
            user-select: none;
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
            justify-content: center;
            align-items: center;
            padding: 35px;
            border: 1px solid #e9cbd5;
            border-radius: 5px;
            background:
                linear-gradient(135deg, rgba(255,239,244,.8), transparent 50%),
                #fffdfd;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            text-align: center;
            overflow-wrap: anywhere;
            box-shadow: 0 8px 25px rgba(151,95,115,.06);
        }

        .face::before {
            content: "✿";
            position: absolute;
            top: 18px;
            left: 20px;
            color: #d99ab0;
            font-size: 19px;
        }

        .face::after {
            content: "♡";
            position: absolute;
            right: 20px;
            bottom: 13px;
            color: #d99ab0;
            font-size: 24px;
        }

        .back {
            transform: rotateY(180deg);
            background:
                linear-gradient(135deg, #fcecf1, transparent 65%),
                #fffafb;
        }

        .face-label {
            margin-bottom: 22px;
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2.5px;
        }

        .face-content {
            max-width: 100%;
            font-family: 'DM Sans', sans-serif;
            font-size: clamp(19px, 3.2vw, 25px);
            font-weight: 600;
            line-height: 1.6;
            letter-spacing: 0;
        }

        .flip-hint {
            margin: 18px 0 23px;
            color: var(--muted);
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            font-style: italic;
            text-align: center;
        }

        .rate-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px;
            margin-bottom: 20px;
        }

        .rate-button {
            min-height: 49px;
            padding: 12px 15px;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: white;
            font-size: 12px;
            font-weight: 800;
            transition: .2s;
        }

        .rate-button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .rate-button.again {
            color: var(--pink-dark);
        }

        .rate-button.again:not(:disabled):hover {
            background: var(--pink-pale);
        }

        .rate-button.known {
            border-color: #d8e5d8;
            color: #79967e;
        }

        .rate-button.known:not(:disabled):hover {
            background: #f2f8f1;
        }

        .swipe-hint {
            margin: 0 0 21px;
            color: var(--muted);
            font-size: 10px;
            text-align: center;
        }

        .controls {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .nav-button {
            min-width: 125px;
            padding: 11px 18px;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: white;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 800;
            transition: .2s;
        }

        .nav-button:hover {
            background: var(--pink-pale);
        }

        .nav-button:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .review-link {
            display: block;
            width: fit-content;
            margin: 22px auto 0;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .generate-button {
            display: block;
            margin: 28px auto 0;
            padding: 13px 23px;
            border: 1px solid var(--pink);
            border-radius: 4px;
            background: var(--pink);
            color: white;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .4px;
            transition: .2s;
        }

        .generate-button:hover {
            background: var(--pink-dark);
            border-color: var(--pink-dark);
        }

        .error-message {
            min-height: 18px;
            margin-top: 12px;
            color: #b75d78;
            font-size: 11px;
            text-align: center;
        }

        .error-message.success {
            color: #79967e;
            font-weight: 700;
        }

        .error-message.warning {
            color: var(--pink-dark);
            font-weight: 700;
        }

        .empty-state {
            padding: 55px 20px;
            border: 1px dashed #e9cbd5;
            background: #fffafb;
            text-align: center;
        }

        .empty-state .symbol {
            color: var(--pink);
            font-size: 37px;
        }

        .empty-state h2 {
            margin: 13px 0 7px;
            font-family: 'Playfair Display', serif;
            font-size: 24px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .footer {
            margin-top: 32px;
            color: var(--muted);
            font-family: 'Playfair Display', serif;
            font-size: 12px;
            font-style: italic;
            text-align: center;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 13px 35px;
            }

            .topbar {
                margin-bottom: 38px;
            }

            .study-panel {
                padding: 19px 13px;
            }

            .stats {
                gap: 7px;
            }

            .stat {
                padding: 13px 5px;
            }

            .stat-number {
                font-size: 23px;
            }

            .stat-label {
                font-size: 8px;
                letter-spacing: .5px;
            }

            #scene {
                height: 290px;
            }

            .face {
                padding: 22px;
            }

            .rate-actions {
                gap: 8px;
            }

            .rate-button {
                padding: 10px 7px;
                font-size: 10px;
            }

            .nav-button {
                min-width: 0;
                flex: 1;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="brand">
            shelly<span>୨୧</span>
        </a>

        <a href="{{ route('sets.show', $set) }}" class="back-link">
            ← Back to study set
        </a>
    </header>

    <section class="heading">
        <span class="eyebrow">YOUR LITTLE STUDY CORNER</span>

        <h1>Flashcard Study</h1>

        <p class="subtitle">{{ $set->title }}</p>

        <div class="ornament">♡ ✿ ୨୧ ✿ ♡</div>
    </section>

    @if ($errors->any())
        <p class="error-message">{{ $errors->first() }}</p>
    @endif

    @if (session('success'))
        <p class="error-message">{{ session('success') }}</p>
    @endif

    <main class="study-panel">

        <section class="stats">
            <div class="stat known">
                <span class="stat-number" id="knownCount">{{ $knownCount }}</span>
                <span class="stat-label">Known</span>
            </div>

            <div class="stat review">
                <span class="stat-number" id="reviewCount">{{ $reviewCount }}</span>
                <span class="stat-label">Need review</span>
            </div>

            <div class="stat total">
                <span class="stat-number" id="reviewedCount">{{ $reviewedCount }}</span>
                <span class="stat-label">Reviewed</span>
            </div>
        </section>

        <div class="progress-heading">
            <span>YOUR LEARNING PROGRESS</span>
            <span id="progressPercentage">
                {{ $set->flashcards()->count() ? round(($reviewedCount / $set->flashcards()->count()) * 100) : 0 }}%
            </span>
        </div>

        <div class="progress-track">
            <div
                class="progress-fill"
                id="progressFill"
                style="width: {{ $set->flashcards()->count() ? ($reviewedCount / $set->flashcards()->count()) * 100 : 0 }}%"
            ></div>
        </div>

        @if ($reviewMode)
            <p class="card-label" style="text-align:center; margin-bottom:20px;">
                CARDS THAT NEED MORE PRACTICE
            </p>
        @endif

        @if ($cards->count())

            @php
    $cardData = $cards->map(fn ($c) => [
        'id' => data_get($c, 'id'),
        'front' => data_get($c, 'front', ''),
        'back' => data_get($c, 'back', ''),
        'known' => (bool) data_get($c, 'known', false),
        'reviewed' => (bool) data_get($c, 'reviewed', false),
        'markUrl' => route(
            'sets.flashcards.mark',
            [$set, data_get($c, 'id')]
        ),
    ])->values();
@endphp

            <div class="card-area">

                <div class="card-meta">
                    <span class="card-label">YOUR FLASHCARD COLLECTION</span>
                    <span class="counter" id="count">
                        1 / {{ $cards->count() }}
                    </span>
                </div>

                <div id="scene" role="button" tabindex="0" aria-label="Flip flashcard">
                    <div id="card">

                        <div class="face">
                            <span class="face-label">QUESTION ♡</span>
                            <span class="face-content" id="frontText"></span>
                        </div>

                        <div class="face back">
                            <span class="face-label">ANSWER ✿</span>
                            <span class="face-content" id="backText"></span>
                        </div>

                    </div>
                </div>

                <p class="flip-hint" id="flipHint">
                    Select the card to reveal the answer.
                </p>

                <div class="rate-actions">
                    <button type="button" class="rate-button again" id="againButton" disabled>
                        ↶ Study Again
                    </button>

                    <button type="button" class="rate-button known" id="knownButton" disabled>
                        I Know This ✓
                    </button>
                </div>

                <p class="swipe-hint">
                    Swipe left for Study Again · Swipe right for I Know This
                </p>

                <div class="controls">
                    <button type="button" class="nav-button" id="prevButton">
                        ← Previous
                    </button>

                    <button type="button" class="nav-button" id="nextButton">
                        Next →
                    </button>
                </div>

                <p class="error-message" id="saveError" role="status" aria-live="polite"></p>

            </div>

        @else

            <div class="empty-state">
                <div class="symbol">✿</div>

                @if ($reviewMode)
                    <h2>All caught up!</h2>
                    <p>You have no reviewed cards waiting for another round.</p>

                    <a class="review-link" href="{{ route('sets.flashcards', $set) }}">
                        Return to all flashcards
                    </a>
                @else
                    <h2>Your study cards are waiting</h2>
                    <p>Create your first set of flashcards from your uploaded study materials.</p>
                @endif
            </div>

        @endif

        @if (!$reviewMode && $reviewCount > 0)
            <a class="review-link" href="{{ route('sets.flashcards', ['set' => $set->id, 'review' => 1]) }}">
                Review the {{ $reviewCount }} cards that need more practice →
            </a>
        @endif

        <form method="POST" action="{{ route('sets.flashcards.make', $set) }}">
            @csrf

            <button type="submit" class="generate-button">
                ✿ {{ $set->flashcards()->count() ? 'Regenerate flashcards' : 'Create flashcards' }}
            </button>
        </form>

    </main>

    <footer class="footer">
        made with love by shelly ♡
    </footer>

</div>

@if ($cards->count())
<script>
    const cards = @json($cardData);

    const logUrl = @json(route('study-log.store'));
    const studySetId = @json($set->id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let currentIndex = 0;
    let saving = false;
    let pointerStartX = null;
    let pointerStartY = null;
    let wasSwipe = false;

    let knownCount = {{ $knownCount }};
    let reviewCount = {{ $reviewCount }};
    let reviewedCount = {{ $reviewedCount }};
    const totalCards = {{ $set->flashcards()->count() }};

    const cardElement = document.getElementById('card');
    const scene = document.getElementById('scene');
    const againButton = document.getElementById('againButton');
    const knownButton = document.getElementById('knownButton');
    const errorElement = document.getElementById('saveError');

    function updateStats() {
        document.getElementById('knownCount').textContent = knownCount;
        document.getElementById('reviewCount').textContent = reviewCount;
        document.getElementById('reviewedCount').textContent = reviewedCount;

        const percentage = totalCards
            ? Math.round((reviewedCount / totalCards) * 100)
            : 0;

        document.getElementById('progressPercentage').textContent =
            percentage + '%';

        document.getElementById('progressFill').style.width =
            percentage + '%';
    }

    function showCard() {
        const current = cards[currentIndex];

        cardElement.classList.remove('flipped');

        document.getElementById('frontText').textContent = current.front;
        document.getElementById('backText').textContent = current.back;

        document.getElementById('count').textContent =
            (currentIndex + 1) + ' / ' + cards.length;

        document.getElementById('prevButton').disabled =
            currentIndex === 0;

        document.getElementById('nextButton').disabled =
            currentIndex === cards.length - 1;

        againButton.disabled = true;
        knownButton.disabled = true;

        errorElement.textContent = '';

        document.getElementById('flipHint').textContent =
            current.reviewed
                ? (current.known
                    ? 'You marked this card as known.'
                    : 'You marked this card for more practice.')
                : 'Select the card to reveal the answer.';
    }

    function flipCard() {
        if (saving) return;

        if (cardElement.classList.contains('flipped')) {
            cardElement.classList.remove('flipped');
            againButton.disabled = true;
            knownButton.disabled = true;
            return;
        }

        cardElement.classList.add('flipped');
        againButton.disabled = false;
        knownButton.disabled = false;

        document.getElementById('flipHint').textContent =
            'How well do you know this topic?';
    }

    function moveCard(direction) {
        const nextIndex = currentIndex + direction;

        if (nextIndex < 0 || nextIndex >= cards.length || saving) {
            return;
        }

        currentIndex = nextIndex;
        showCard();
    }

    async function rateCard(known) {
        if (saving || !cardElement.classList.contains('flipped')) {
            return;
        }

        const current = cards[currentIndex];

        // Do not send another save request if this card already has
        // the same saved status.
        if (current.reviewed && current.known === known) {
            errorElement.className = 'error-message warning';
            errorElement.textContent = known
                ? '♡ This card is already saved as Known!'
                : '♡ This card is already saved under Need Review!';

            document.getElementById('flipHint').textContent = known
                ? 'This card is already marked as Known.'
                : 'This card is already marked for more practice.';

            return;
        }

        saving = true;
        againButton.disabled = true;
        knownButton.disabled = true;

        const wasReviewed = current.reviewed;
        const wasKnown = current.known;

        errorElement.className = 'error-message';
        errorElement.textContent = 'Saving your progress...';

        try {
            const response = await fetch(current.markUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ known: known })
            });

            if (!response.ok) {
                throw new Error('Your learning progress could not be saved.');
            }

            // Update the counters only after the server confirms the save.
            if (wasReviewed) {
                if (wasKnown) {
                    knownCount--;
                } else {
                    reviewCount--;
                }
            } else {
                reviewedCount++;
            }

            if (known) {
                knownCount++;
            } else {
                reviewCount++;
            }

            current.known = known;
            current.reviewed = true;

            updateStats();

            errorElement.className = 'error-message success';
            errorElement.textContent = known
                ? '♡ Saved! This card is now marked as Known.'
                : '♡ Saved! This card is now marked for Need Review.';

            document.getElementById('flipHint').textContent = known
                ? 'This card is saved as Known.'
                : 'This card is saved for another round of practice.';

            // Record study activity only the first time this card is reviewed.
            if (!wasReviewed) {
                try {
                    const logResponse = await fetch(logUrl, {
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

                    if (!logResponse.ok) {
                        console.warn('Points could not be logged.');
                    }
                } catch (logError) {
                    console.error('Points could not be logged:', logError);
                }
            }

            // Leave enough time for the learner to read the confirmation.
            if (currentIndex < cards.length - 1) {
                setTimeout(() => {
                    moveCard(1);
                }, 1200);
            }

        } catch (error) {
            console.error(error);

            errorElement.className = 'error-message';
            errorElement.textContent =
                'Could not save your progress. Please try again.';

            againButton.disabled = false;
            knownButton.disabled = false;

        } finally {
            saving = false;
        }
    }

    scene.addEventListener('click', function() {
        if (wasSwipe) {
            wasSwipe = false;
            return;
        }

        flipCard();
    });

    scene.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            flipCard();
        }
    });

    scene.addEventListener('pointerdown', function(event) {
        pointerStartX = event.clientX;
        pointerStartY = event.clientY;
        wasSwipe = false;
    });

    scene.addEventListener('pointerup', function(event) {
        if (pointerStartX === null || pointerStartY === null) {
            return;
        }

        const differenceX = event.clientX - pointerStartX;
        const differenceY = event.clientY - pointerStartY;

        pointerStartX = null;
        pointerStartY = null;

        if (
            Math.abs(differenceX) < 75 ||
            Math.abs(differenceX) < Math.abs(differenceY) * 1.2
        ) {
            return;
        }

        if (!cardElement.classList.contains('flipped')) {
            return;
        }

        wasSwipe = true;

        if (differenceX > 0) {
            rateCard(true);
        } else {
            rateCard(false);
        }
    });

    scene.addEventListener('pointercancel', function() {
        pointerStartX = null;
        pointerStartY = null;
    });

    againButton.addEventListener('click', () => rateCard(false));
    knownButton.addEventListener('click', () => rateCard(true));

    document.getElementById('prevButton').addEventListener('click', () => {
        moveCard(-1);
    });

    document.getElementById('nextButton').addEventListener('click', () => {
        moveCard(1);
    });

    showCard();
    updateStats();
</script>
@endif

</body>
</html>
