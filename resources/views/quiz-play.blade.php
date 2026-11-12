
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shelly | Take Your Quiz</title>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #d98eaa;
            --pink-dark: #b97891;
            --pink-deep: #795568;
            --pink-light: #fff0f5;
            --pink-soft: #fff8fb;
            --cream: #fffdfc;
            --ink: #795568;
            --muted: #a18c96;
            --line: #efdde4;
            --green: #739b80;
            --green-bg: #eff7f0;
            --red: #ad6876;
            --red-bg: #fff1f3;
            --shadow: 0 8px 25px rgba(151, 95, 115, .05);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 25px 20px 60px;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 5% 5%, #fff0f5 0, transparent 28%),
                radial-gradient(circle at 95% 90%, #fdf0f5 0, transparent 30%),
                var(--cream);
        }

        button, a {
            -webkit-tap-highlight-color: transparent;
        }

        button {
            font: inherit;
        }

        .page {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
        }

        /* Navigation */

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 42px;
        }

        .brand {
            color: var(--pink-dark);
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -.7px;
            text-decoration: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: #fffdfd;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .back-link:hover {
            color: var(--pink-dark);
            border-color: var(--pink);
            background: var(--pink-light);
        }

        /* Page Heading */

        .heading {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            padding: 9px 2px;
        }

        .heading-content {
            flex: 1;
            min-width: 0;
        }

        .eyebrow {
            display: block;
            margin-bottom: 8px;
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            color: var(--pink-deep);
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 5vw, 36px);
            font-weight: 600;
            letter-spacing: -.5px;
        }

        .subtitle {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .heading-decoration {
            display: flex;
            flex: 0 0 65px;
            align-items: center;
            justify-content: center;
            width: 65px;
            height: 65px;
            border: 1px solid #efd4df;
            border-radius: 21px;
            background: #fff1f6;
            color: #cf91aa;
            font-size: 27px;
        }

        /* Main Card */

        .quiz-card {
            padding: 28px;
            border: 1px solid var(--line);
            border-radius: 19px;
            background: rgba(255, 255, 255, .96);
            box-shadow: var(--shadow);
        }

        /* Quiz Overview */

        .quiz-overview {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .overview-label {
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border: 1px solid #efdde4;
            border-radius: 30px;
            background: #fff8fb;
            color: var(--pink-dark);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #c982a1;
            box-shadow: 0 0 0 3px #fbedf3;
        }

        /* Statistics */

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .stat {
            position: relative;
            overflow: hidden;
            padding: 16px 12px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fffafb;
            text-align: center;
        }

        .stat-label {
            display: block;
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .stat-value {
            color: var(--pink-dark);
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
        }

        /* Progress */

        .progress-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 9px;
        }

        .progress-heading span:first-child {
            color: var(--ink);
            font-size: 11px;
            font-weight: 700;
        }

        .progress-percent {
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
        }

        .progress-track {
            height: 8px;
            overflow: hidden;
            margin-bottom: 27px;
            border-radius: 30px;
            background: #f5e8ed;
        }

        .progress-fill {
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #e9b4c9, #c982a1);
            transition: width .4s ease;
        }

        /* Question Panel */

        .question-panel {
            padding: 23px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fffafb;
        }

        .question-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 17px;
        }

        .question-number {
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .question-type {
            padding: 6px 11px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: #fff;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
        }

        /* Normal font for the actual question */

        #question {
            margin: 0;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: clamp(17px, 3vw, 20px);
            font-weight: 700;
            line-height: 1.65;
            letter-spacing: 0;
        }

        .answer-instruction {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .options-heading {
            margin: 25px 0 13px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        /* Answer Options — intentionally preserved */

        #options {
            display: grid;
            gap: 11px;
        }

        .opt {
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            min-height: 61px;
            padding: 13px 16px;
            border: 1px solid var(--line);
            border-radius: 15px;
            background: #fff;
            color: var(--ink);
            text-align: left;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.6;
            cursor: pointer;
            transition: border-color .2s ease, background .2s ease, transform .2s ease;
        }

        .option-letter {
            display: flex;
            flex: 0 0 32px;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid #efdee5;
            border-radius: 10px;
            background: #fff7fa;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 800;
        }

        .option-text {
            flex: 1;
        }

        .option-indicator {
            color: var(--muted);
            font-size: 17px;
            font-weight: 700;
        }

        .opt:hover:not(:disabled) {
            border-color: #dca1b9;
            background: #fff6f9;
            transform: translateY(-2px);
        }

        .opt:focus-visible {
            outline: 3px solid rgba(217,145,173,.3);
            outline-offset: 3px;
        }

        .opt:disabled {
            opacity: 1;
            cursor: default;
        }

        .opt.right {
            border-color: #a7cbb0;
            background: var(--green-bg);
            color: #527b5d;
        }

        .opt.right .option-letter {
            border-color: #a7cbb0;
            background: #e4f1e6;
            color: #527b5d;
        }

        .opt.wrong {
            border-color: #e6a0ae;
            background: var(--red-bg);
            color: var(--red);
        }

        .opt.wrong .option-letter {
            border-color: #e6a0ae;
            background: #ffe5e9;
            color: var(--red);
        }

        /* Feedback — intentionally preserved */

        #feedback {
            margin-top: 22px;
            padding: 20px;
            border: 1px solid var(--line);
            border-radius: 17px;
            background: #fff9fb;
            color: var(--ink);
            font-size: 12px;
            line-height: 1.8;
            animation: fadeUp .3s ease;
        }

        .feedback-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 13px;
        }

        .feedback-symbol {
            display: flex;
            flex: 0 0 34px;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 11px;
            background: var(--pink-light);
            color: var(--pink-dark);
            font-size: 16px;
            font-weight: 800;
        }

        .feedback-title {
            margin: 0;
            color: var(--pink-dark);
            font-size: 14px;
            font-weight: 800;
        }

        .feedback-message {
            margin: 0 0 12px;
            color: var(--muted);
            font-size: 11px;
        }

        .feedback-detail {
            margin-top: 10px;
            padding: 12px 14px;
            border-radius: 11px;
            background: #fff;
        }

        .feedback-detail strong {
            display: block;
            margin-bottom: 4px;
            color: var(--pink-dark);
            font-size: 10px;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .feedback-detail p {
            margin: 0;
            color: var(--ink);
            font-size: 12px;
            line-height: 1.7;
        }

        .hidden {
            display: none !important;
        }

        /* Save Status — intentionally preserved */

        .save-status {
            min-height: 18px;
            margin: 13px 0 0;
            color: var(--muted);
            text-align: center;
            font-size: 10px;
            line-height: 1.6;
        }

        /* Next Button — intentionally preserved */

        .next-button {
            display: block;
            width: 100%;
            margin-top: 22px;
            padding: 16px 20px;
            border: 0;
            border-radius: 30px;
            background: #a85e7d;
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .3px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(168, 94, 125, .17);
            transition: .2s ease;
        }

        .next-button:hover {
            background: #884b67;
            transform: translateY(-2px);
            box-shadow: 0 9px 20px rgba(168, 94, 125, .2);
        }

        .next-button:focus-visible,
        .settings-button:focus-visible {
            outline: 3px solid rgba(217,145,173,.35);
            outline-offset: 4px;
        }

        /* Popup */

        #popup {
            position: fixed;
            top: 22px;
            left: 50%;
            z-index: 999;
            max-width: 90%;
            padding: 13px 22px;
            border: 1px solid #edc5d6;
            border-radius: 999px;
            background: #fce5ee;
            color: #8d4967;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
            box-shadow: 0 8px 25px rgba(145, 91, 115, .15);
            pointer-events: none;
            opacity: 0;
            transform: translateX(-50%) scale(.8);
        }

        #popup.show {
            animation: pop 2s ease forwards;
        }

        #popup.sad {
            border-color: #ddcbea;
            background: #eee5f7;
            color: #70518b;
        }

        @keyframes pop {
            0% {
                transform: translateX(-50%) scale(.8);
                opacity: 0;
            }
            15% {
                transform: translateX(-50%) scale(1.05);
                opacity: 1;
            }
            25%, 85% {
                transform: translateX(-50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translateX(-50%) scale(.95);
                opacity: 0;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Results */

        .result {
            padding: 25px 12px;
            text-align: center;
            animation: fadeUp .4s ease;
        }

        .result-decoration {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            margin: 0 auto 20px;
            border: 1px solid #efd7e1;
            border-radius: 22px;
            background: #fff4f8;
            font-size: 35px;
        }

        .result-kicker {
            display: block;
            margin-bottom: 9px;
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .result h2,
        .empty-state h2 {
            margin: 0 0 12px;
            color: var(--pink-deep);
            font-family: 'Playfair Display', serif;
            font-size: clamp(27px, 5vw, 34px);
            font-weight: 600;
            letter-spacing: -.5px;
        }

        .result-description {
            max-width: 400px;
            margin: 0 auto;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .result-score {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            min-width: 190px;
            margin: 25px 0 18px;
            padding: 20px 30px;
            border: 1px solid #ecd8e1;
            border-radius: 17px;
            background: linear-gradient(145deg, #fff4f8, #fbedf3);
        }

        .result-score-number {
            color: var(--pink-dark);
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 600;
        }

        .result-score-label {
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .result-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            max-width: 390px;
            margin: 0 auto 23px;
        }

        .result-stat {
            padding: 15px 10px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fffafb;
        }

        .result-stat strong {
            display: block;
            margin-bottom: 5px;
            color: var(--pink-dark);
            font-family: 'Playfair Display', serif;
            font-size: 22px;
        }

        .result-stat span {
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .result-actions {
            max-width: 390px;
            margin: 0 auto;
        }

        .settings-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 12px;
            padding: 14px 22px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: white;
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            transition: .2s ease;
        }

        .settings-button:hover {
            border-color: var(--pink);
            background: var(--pink-light);
            transform: translateY(-1px);
        }

        /* Empty State */

        .empty-state {
            padding: 40px 12px;
            text-align: center;
        }

        .empty-state-decoration {
            margin-bottom: 17px;
            font-size: 35px;
        }

        .empty-state p {
            max-width: 350px;
            margin: 0 auto;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        /* Responsive */

        @media (max-width: 600px) {
            body {
                padding: 19px 13px 40px;
            }

            .topbar {
                margin-bottom: 30px;
            }

            .brand {
                font-size: 24px;
            }

            .back-link {
                padding: 9px 12px;
                font-size: 10px;
            }

            .heading {
                margin-bottom: 20px;
            }

            .heading-decoration {
                flex-basis: 53px;
                width: 53px;
                height: 53px;
                border-radius: 17px;
                font-size: 23px;
            }

            .quiz-card {
                padding: 19px 15px;
                border-radius: 17px;
            }

            .stats {
                gap: 7px;
            }

            .stat {
                padding: 14px 5px;
                border-radius: 12px;
            }

            .stat-label {
                font-size: 8px;
                letter-spacing: .7px;
            }

            .stat-value {
                font-size: 22px;
            }

            .question-panel {
                padding: 18px 15px;
            }

            #question {
                font-size: 17px;
            }

            .opt {
                min-height: 57px;
                padding: 11px;
                gap: 11px;
                font-size: 12px;
            }

            .option-letter {
                flex-basis: 29px;
                width: 29px;
                height: 29px;
            }

            #feedback {
                padding: 16px;
            }

            .result {
                padding: 18px 3px;
            }
        }

        @media (max-width: 360px) {
            .quiz-overview {
                align-items: flex-start;
                flex-direction: column;
            }

            .heading-decoration {
                display: none;
            }

            .result-stats {
                gap: 8px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
    <div id="popup" role="status" aria-live="polite"></div>

    <main class="page">

        <nav class="topbar">
            <a href="{{ route('dashboard') }}" class="brand">🎀 shelly</a>

            <a href="{{ route('sets.quiz', $set) }}" class="back-link">
                ← Quiz settings
            </a>
        </nav>

        <header class="heading">
            <div class="heading-content">
                <span class="eyebrow">Your little study session</span>

                <h1>Little Quiz Time!</h1>

                <p class="subtitle">
                    {{ $set->title }}
                </p>
            </div>

            <div class="heading-decoration">୨୧</div>
        </header>

        <section class="quiz-card">

            @if (session('success'))
                <p class="save-status">{{ session('success') }}</p>
            @endif

            @if (count($questions))

                <div id="game">

                    <div class="quiz-overview">
                        <span class="overview-label">Your quiz progress</span>

                        <span class="live-indicator">
                            <span class="live-dot"></span>
                            QUIZ IN PROGRESS
                        </span>
                    </div>

                    <div class="stats">

                        <div class="stat">
                            <span class="stat-label">Question</span>

                            <span class="stat-value" id="progress">
                                1/{{ count($questions) }}
                            </span>
                        </div>

                        <div class="stat">
                            <span class="stat-label">Score</span>

                            <span class="stat-value" id="score">0</span>
                        </div>

                        <div class="stat">
                            <span class="stat-label">Streak</span>

                            <span class="stat-value" id="streak">0</span>
                        </div>

                    </div>

                    <div class="progress-heading">
                        <span>Overall progress</span>

                        <span class="progress-percent" id="progressPercent">
                            0%
                        </span>
                    </div>

                    <div class="progress-track">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>

                    <div class="question-panel">

                        <div class="question-meta">
                            <span class="question-number" id="questionNumber">
                                QUESTION 01
                            </span>

                            <span class="question-type" id="questionType">
                                Multiple Choice
                            </span>
                        </div>

                        <h2 id="question"></h2>

                        <p class="answer-instruction">
                            Take your time and choose the best answer.
                        </p>

                    </div>

                    <p class="options-heading">Choose your answer</p>

                    <div id="options"></div>

                    <div id="feedback" class="hidden" aria-live="polite"></div>

                    <p id="saveStatus" class="save-status" aria-live="polite"></p>

                    <button
                        id="next"
                        class="next-button hidden"
                        type="button"
                    >
                        Next question →
                    </button>

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-state-decoration">🎀</div>

                    <h2>No quiz questions yet!</h2>

                    <p>
                        Generate a quiz first so Shelly can prepare some
                        questions for your study session.
                    </p>

                    <a href="{{ route('sets.quiz', $set) }}" class="settings-button">
                        Create a quiz
                    </a>

                </div>

            @endif

        </section>

    </main>

    @if (count($questions))

        <script>
            const qs = @json($questions);
            const lines = @json(config('shelly'));

            const logUrl = @json(route('study-log.store'));
            const studySetId = @json($set->id);

            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            ).content;

            let i = 0;
            let score = 0;
            let streak = 0;
            let answered = false;
            let audioCtx = null;

            function pick(list, fallback) {
                return Array.isArray(list) && list.length
                    ? list[Math.floor(Math.random() * list.length)]
                    : fallback;
            }

            function tone(freq, start, duration, type) {
                try {
                    audioCtx = audioCtx ||
                        new (
                            window.AudioContext ||
                            window.webkitAudioContext
                        )();

                    const oscillator = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();

                    oscillator.type = type;
                    oscillator.frequency.value = freq;

                    gain.gain.setValueAtTime(
                        0.18,
                        audioCtx.currentTime + start
                    );

                    gain.gain.exponentialRampToValueAtTime(
                        0.001,
                        audioCtx.currentTime + start + duration
                    );

                    oscillator.connect(gain);
                    gain.connect(audioCtx.destination);

                    oscillator.start(audioCtx.currentTime + start);

                    oscillator.stop(
                        audioCtx.currentTime + start + duration
                    );

                } catch (error) {
                    console.warn('Audio is unavailable:', error);
                }
            }

            function playDing() {
                tone(880, 0, 0.25, 'sine');
                tone(1320, 0.12, 0.4, 'sine');
            }

            function playBuzz() {
                tone(160, 0, 0.35, 'sawtooth');
            }

            function showPopup(message, sad = false) {
                const popup = document.getElementById('popup');

                popup.textContent = message;
                popup.className = '';

                void popup.offsetWidth;

                popup.className = 'show' + (sad ? ' sad' : '');
            }

            async function saveAnswer(question, correct) {
                const status = document.getElementById('saveStatus');

                if (!question.id) {
                    status.textContent =
                        'This question has no database ID, so its progress was not saved.';

                    return;
                }

                status.textContent = 'Saving your progress...';

                try {
                    const response = await fetch(logUrl, {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        body: JSON.stringify({
                            study_set_id: studySetId,
                            question_id: question.id,
                            type: 'quiz',
                            correct: correct
                        })
                    });

                    if (!response.ok) {
                        status.textContent =
                            'Progress could not be saved. Please check your connection.';

                        return;
                    }

                    const result = await response.json();

                    status.textContent = result.ok
                        ? 'Progress saved!'
                        : 'Progress could not be saved.';

                } catch (error) {
                    console.error(error);

                    status.textContent =
                        'Connection problem. Your answer was not saved.';
                }
            }

            function render() {
                answered = false;

                const question = qs[i];

                const currentNumber = String(i + 1).padStart(2, '0');

                document.getElementById('progress').textContent =
                    (i + 1) + '/' + qs.length;

                const percentage = Math.round((i / qs.length) * 100);

                document.getElementById('progressFill').style.width =
                    percentage + '%';

                document.getElementById('progressPercent').textContent =
                    percentage + '%';

                document.getElementById('questionNumber').textContent =
                    'QUESTION ' + currentNumber;

                document.getElementById('questionType').textContent =
                    question.type === 'true_false'
                        ? 'True or False'
                        : 'Multiple Choice';

                document.getElementById('question').textContent =
                    question.question;

                document.getElementById('feedback').classList.add('hidden');

                document.getElementById('next').classList.add('hidden');

                document.getElementById('saveStatus').textContent = '';

                const box = document.getElementById('options');

                box.innerHTML = '';

                question.options.forEach((text, idx) => {
                    const button = document.createElement('button');

                    button.type = 'button';
                    button.className = 'opt';

                    const letter = document.createElement('span');
                    letter.className = 'option-letter';
                    letter.textContent = String.fromCharCode(65 + idx);

                    const optionText = document.createElement('span');
                    optionText.className = 'option-text';
                    optionText.textContent = text;

                    const indicator = document.createElement('span');
                    indicator.className = 'option-indicator';
                    indicator.textContent = '›';

                    button.append(letter, optionText, indicator);

                    button.addEventListener('click', () => choose(idx));

                    box.appendChild(button);
                });
            }

            function choose(idx) {
                if (answered) return;

                answered = true;

                const question = qs[i];

                const buttons = document.querySelectorAll('.opt');

                const correct =
                    idx === Number(question.correct_index);

                buttons.forEach(button => {
                    button.disabled = true;
                });

                if (buttons[question.correct_index]) {
                    buttons[question.correct_index].classList.add('right');

                    buttons[question.correct_index]
                        .querySelector('.option-indicator')
                        .textContent = '✓';
                }

                if (correct) {
                    score++;
                    streak++;

                    playDing();

                    if (typeof confetti === 'function') {
                        confetti({
                            particleCount: 90,
                            spread: 70,
                            origin: { y: 0.7 },
                            colors: [
                                '#fbcfe0',
                                '#f4b0cc',
                                '#fbcfe8',
                                '#ffffff'
                            ]
                        });
                    }

                    const message =
                        (streak === 3 || streak === 5)
                            ? pick(lines.streak, 'Amazing streak! 🎀')
                            : pick(lines.correct, 'You got it!');

                    showPopup(message);

                } else {
                    streak = 0;

                    if (buttons[idx]) {
                        buttons[idx].classList.add('wrong');

                        buttons[idx]
                            .querySelector('.option-indicator')
                            .textContent = '×';
                    }

                    playBuzz();

                    showPopup(
                        pick(lines.wrong, 'Keep learning! 🌷'),
                        true
                    );
                }

                document.getElementById('score').textContent = score;

                document.getElementById('streak').textContent = streak;

                saveAnswer(question, correct);

                const feedback = document.getElementById('feedback');

                feedback.innerHTML = '';

                const heading = document.createElement('div');
                heading.className = 'feedback-heading';

                const symbol = document.createElement('div');
                symbol.className = 'feedback-symbol';
                symbol.textContent = correct ? '✓' : '!';

                const title = document.createElement('p');
                title.className = 'feedback-title';
                title.textContent = correct
                    ? 'Correct! Well done!'
                    : 'Not quite! Keep going!';

                heading.append(symbol, title);

                const message = document.createElement('p');
                message.className = 'feedback-message';
                message.textContent = correct
                    ? 'You earned 10 points for this answer.'
                    : 'You earned 2 points for trying. Every question is a chance to learn!';

                const answerDetail = document.createElement('div');
                answerDetail.className = 'feedback-detail';

                const answerLabel = document.createElement('strong');
                answerLabel.textContent = 'Correct answer';

                const answerText = document.createElement('p');
                answerText.textContent =
                    question.options[question.correct_index];

                answerDetail.append(answerLabel, answerText);

                const explanationDetail = document.createElement('div');
                explanationDetail.className = 'feedback-detail';

                const explanationLabel = document.createElement('strong');
                explanationLabel.textContent = 'A little explanation';

                const explanationText = document.createElement('p');
                explanationText.textContent =
                    question.explanation ||
                    'Keep reviewing this topic to learn more!';

                explanationDetail.append(
                    explanationLabel,
                    explanationText
                );

                feedback.append(
                    heading,
                    message,
                    answerDetail,
                    explanationDetail
                );

                feedback.classList.remove('hidden');

                const nextButton = document.getElementById('next');

                nextButton.textContent =
                    i === qs.length - 1
                        ? 'See my results →'
                        : 'Next question →';

                nextButton.classList.remove('hidden');

                nextButton.focus();
            }

            function next() {
                i++;

                if (i >= qs.length) {
                    finish();
                } else {
                    render();

                    document.getElementById('question').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            function finish() {
                document.getElementById('progressFill').style.width = '100%';

                document.getElementById('progressPercent').textContent =
                    '100%';

                const game = document.getElementById('game');

                game.innerHTML = '';

                const result = document.createElement('div');
                result.className = 'result';

                const decoration = document.createElement('div');
                decoration.className = 'result-decoration';
                decoration.textContent = '🎀';

                const kicker = document.createElement('span');
                kicker.className = 'result-kicker';
                kicker.textContent = 'Quiz completed';

                const heading = document.createElement('h2');
                heading.textContent = 'You did it!';

                const description = document.createElement('p');
                description.className = 'result-description';
                description.textContent = pick(
                    lines.done,
                    'Every little step counts. Keep learning!'
                );

                const scoreBox = document.createElement('div');
                scoreBox.className = 'result-score';

                const scoreNumber = document.createElement('span');
                scoreNumber.className = 'result-score-number';
                scoreNumber.textContent = score + ' / ' + qs.length;

                const scoreLabel = document.createElement('span');
                scoreLabel.className = 'result-score-label';
                scoreLabel.textContent = 'Correct answers';

                scoreBox.append(scoreNumber, scoreLabel);

                const resultStats = document.createElement('div');
                resultStats.className = 'result-stats';

                const pointsStat = document.createElement('div');
                pointsStat.className = 'result-stat';

                const pointsValue = document.createElement('strong');
                pointsValue.textContent =
                    (score * 10) + ((qs.length - score) * 2);

                const pointsLabel = document.createElement('span');
                pointsLabel.textContent = 'Points earned';

                pointsStat.append(pointsValue, pointsLabel);

                const accuracyStat = document.createElement('div');
                accuracyStat.className = 'result-stat';

                const accuracyValue = document.createElement('strong');
                accuracyValue.textContent =
                    Math.round((score / qs.length) * 100) + '%';

                const accuracyLabel = document.createElement('span');
                accuracyLabel.textContent = 'Accuracy';

                accuracyStat.append(accuracyValue, accuracyLabel);

                resultStats.append(pointsStat, accuracyStat);

                const actions = document.createElement('div');
                actions.className = 'result-actions';

                const again = document.createElement('button');
                again.type = 'button';
                again.className = 'next-button';
                again.textContent = 'Try again';
                again.addEventListener('click', () => location.reload());

                const settings = document.createElement('a');
                settings.href = @json(route('sets.quiz', $set));
                settings.className = 'settings-button';
                settings.textContent = 'Create another quiz';

                actions.append(again, settings);

                result.append(
                    decoration,
                    kicker,
                    heading,
                    description,
                    scoreBox,
                    resultStats,
                    actions
                );

                game.appendChild(result);

                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 200,
                        spread: 100,
                        origin: { y: 0.6 },
                        colors: [
                            '#fbcfe0',
                            '#f4b0cc',
                            '#fbcfe8'
                        ]
                    });
                }

                result.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            document.getElementById('next').addEventListener('click', next);

            render();
        </script>

    @endif

</body>
</html>
