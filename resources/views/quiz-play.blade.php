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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #e99bb9;
            --pink-dark: #a85e7d;
            --pink-light: #fff0f6;
            --pink-soft: #fff8fb;
            --cream: #fffdfb;
            --ink: #604b56;
            --muted: #a58c98;
            --line: #f3dce6;
            --green: #8bbd9b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 28px 16px 60px;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 10%, #ffe8f1 0, transparent 28%),
                radial-gradient(circle at 90% 85%, #f9e8f3 0, transparent 30%),
                var(--cream);
        }

        .page {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .brand {
            color: var(--pink-dark);
            font-family: 'Fredoka', sans-serif;
            font-size: 25px;
            font-weight: 600;
            text-decoration: none;
        }

        .back-link {
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            background: white;
            border: 1px solid var(--line);
            border-radius: 30px;
            padding: 10px 15px;
        }

        .heading {
            text-align: center;
            margin-bottom: 24px;
        }

        .bow {
            font-size: 34px;
            margin-bottom: 5px;
        }

        h1 {
            margin: 0;
            font-family: 'Fredoka', sans-serif;
            font-size: clamp(25px, 6vw, 34px);
            color: var(--pink-dark);
        }

        .subtitle {
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
        }

        .quiz-card {
            background: rgba(255,255,255,.94);
            border: 1px solid var(--line);
            border-radius: 27px;
            padding: 26px;
            box-shadow: 0 12px 35px rgba(173, 105, 137, .09);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
            margin-bottom: 24px;
        }

        .stat {
            padding: 13px 7px;
            text-align: center;
            background: var(--pink-soft);
            border: 1px solid var(--line);
            border-radius: 16px;
        }

        .stat-label {
            display: block;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-family: 'Fredoka', sans-serif;
            color: var(--pink-dark);
            font-size: 22px;
        }

        .progress-track {
            height: 8px;
            background: #f9e7ef;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .progress-fill {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #f3b5ce, #d88aaa);
            border-radius: inherit;
            transition: width .3s ease;
        }

        .question-label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        #question {
            font-family: 'Fredoka', sans-serif;
            font-size: 22px;
            line-height: 1.4;
            margin: 0 0 22px;
            color: var(--ink);
        }

        #options {
            display: grid;
            gap: 11px;
        }

        .opt {
            display: block;
            width: 100%;
            padding: 15px 17px;
            text-align: left;
            color: var(--ink);
            background: var(--pink-soft);
            border: 1.5px solid var(--line);
            border-radius: 15px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
        }

        .opt:hover:not(:disabled) {
            background: var(--pink-light);
            border-color: var(--pink);
            transform: translateY(-1px);
        }

        .opt:disabled {
            cursor: default;
            opacity: 1;
        }

        .opt.right {
            background: #edf8f0;
            border-color: var(--green);
            color: #527b5d;
        }

        .opt.wrong {
            background: #fff0f2;
            border-color: #e6a0ae;
            color: #a85e6d;
        }

        #feedback {
            margin-top: 19px;
            padding: 15px 17px;
            border-radius: 15px;
            background: #fff5f9;
            border: 1px dashed #eec5d6;
            font-size: 13px;
            line-height: 1.7;
        }

        #feedback p { margin: 5px 0; }

        .feedback-title {
            color: var(--pink-dark);
            font-weight: 800;
        }

        .hidden { display: none !important; }

        .next-button {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            color: white;
            background: var(--pink-dark);
            border: 0;
            border-radius: 30px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .next-button:hover {
            background: #82435f;
            transform: translateY(-1px);
        }

        .save-status {
            min-height: 18px;
            margin-top: 12px;
            text-align: center;
            font-size: 11px;
            color: var(--muted);
        }

        #popup {
            position: fixed;
            top: 20px;
            left: 50%;
            z-index: 999;
            max-width: 90%;
            padding: 13px 22px;
            border-radius: 999px;
            background: #f4b0cc;
            color: #7a2e52;
            font-size: 15px;
            font-weight: 800;
            box-shadow: 0 6px 20px rgba(244,114,182,.3);
            pointer-events: none;
            text-align: center;
            opacity: 0;
            transform: translateX(-50%) scale(.5);
        }

        #popup.show {
            animation: pop 2s ease forwards;
        }

        #popup.sad {
            background: #e7d6f5;
            color: #70518b;
        }

        @keyframes pop {
            0% {
                transform: translateX(-50%) scale(.5);
                opacity: 0;
            }
            15% {
                transform: translateX(-50%) scale(1.1);
                opacity: 1;
            }
            25%, 85% {
                transform: translateX(-50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translateX(-50%) scale(.9);
                opacity: 0;
            }
        }

        .result {
            text-align: center;
            padding: 18px 5px;
        }

        .result-icon {
            font-size: 54px;
            margin-bottom: 12px;
        }

        .result h2 {
            font-family: 'Fredoka', sans-serif;
            color: var(--pink-dark);
            font-size: 29px;
            margin: 0 0 10px;
        }

        .result p {
            color: var(--muted);
            line-height: 1.7;
            font-size: 14px;
        }

        .result-score {
            display: inline-block;
            margin: 15px 0;
            padding: 12px 22px;
            border-radius: 20px;
            background: var(--pink-light);
            color: var(--pink-dark);
            font-family: 'Fredoka', sans-serif;
            font-size: 24px;
        }

        .empty-state {
            text-align: center;
            padding: 30px 10px;
        }

        .empty-state h2 {
            font-family: 'Fredoka', sans-serif;
            color: var(--pink-dark);
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .settings-button {
            display: inline-block;
            margin-top: 12px;
            padding: 12px 22px;
            border-radius: 30px;
            background: var(--pink);
            color: white;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
        }

        @media (max-width: 480px) {
            body { padding: 18px 12px 40px; }
            .quiz-card { padding: 20px 16px; }
            #question { font-size: 20px; }
            .opt { padding: 14px; }
        }
    </style>
</head>

<body>
    <div id="popup"></div>

    <main class="page">

        <nav class="topbar">
            <a href="{{ route('dashboard') }}" class="brand">🎀 shelly</a>

            <a href="{{ route('sets.quiz', $set) }}" class="back-link">
                ← Quiz settings
            </a>
        </nav>

        <header class="heading">
            <div class="bow">🎀</div>
            <h1>Little Quiz Time!</h1>
            <p class="subtitle">{{ $set->title }}</p>
        </header>

        <section class="quiz-card">

            @if (session('success'))
                <p class="save-status">{{ session('success') }}</p>
            @endif

            @if (count($questions))
                <div id="game">

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

                    <div class="progress-track">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>

                    <p class="question-label">♡ Your question</p>

                    <h2 id="question"></h2>

                    <div id="options"></div>

                    <div id="feedback" class="hidden"></div>

                    <p id="saveStatus" class="save-status" aria-live="polite"></p>

                    <button
                        id="next"
                        class="next-button hidden"
                        type="button"
                    >
                        Next →
                    </button>

                </div>
            @else
                <div class="empty-state">
                    <div class="result-icon">🌷</div>
                    <h2>No quiz questions yet!</h2>
                    <p>
                        Generate a quiz first so Shelly can prepare your questions.
                    </p>

                    <a href="{{ route('sets.quiz', $set) }}" class="settings-button">
                        Create a quiz ♡
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
                        '⚠️ This question has no database ID, so its progress was not saved.';

                    return;
                }

                status.textContent = '♡ Saving your progress...';

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
                            '⚠️ Progress could not be saved. Please check your connection.';
                        return;
                    }

                    const result = await response.json();

                    status.textContent = result.ok
                        ? '♡ Progress saved!'
                        : '⚠️ Progress could not be saved.';

                } catch (error) {
                    console.error(error);

                    status.textContent =
                        '⚠️ Connection problem. Your answer was not saved.';
                }
            }

            function render() {
                answered = false;

                const question = qs[i];

                document.getElementById('progress').textContent =
                    (i + 1) + '/' + qs.length;

                document.getElementById('progressFill').style.width =
                    ((i / qs.length) * 100) + '%';

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
                    button.textContent = text;
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
                            colors: ['#fbcfe0', '#f4b0cc', '#fbcfe8', '#ffffff']
                        });
                    }

                    const message =
                        (streak === 3 || streak === 5)
                            ? pick(lines.streak, 'Amazing streak! 🎀')
                            : pick(lines.correct, 'You got it! ♡');

                    showPopup(message);

                } else {
                    streak = 0;

                    if (buttons[idx]) {
                        buttons[idx].classList.add('wrong');
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

                const title = document.createElement('p');
                title.className = 'feedback-title';
                title.textContent = correct
                    ? '♡ Correct! You earned 10 points!'
                    : '♡ Not quite! You earned 2 points for trying.';

                const answer = document.createElement('p');
                answer.textContent =
                    'Correct answer: ' +
                    question.options[question.correct_index];

                const explanation = document.createElement('p');
                explanation.textContent =
                    '💡 Why: ' +
                    (
                        question.explanation ||
                        'Keep reviewing this topic to learn more!'
                    );

                feedback.append(title, answer, explanation);
                feedback.classList.remove('hidden');

                const nextButton = document.getElementById('next');

                nextButton.textContent =
                    i === qs.length - 1
                        ? 'See my results 🎉'
                        : 'Next question →';

                nextButton.classList.remove('hidden');
            }

            function next() {
                i++;

                if (i >= qs.length) {
                    finish();
                } else {
                    render();
                }
            }

            function finish() {
                document.getElementById('progressFill').style.width = '100%';

                const game = document.getElementById('game');
                game.innerHTML = '';

                const result = document.createElement('div');
                result.className = 'result';

                const icon = document.createElement('div');
                icon.className = 'result-icon';
                icon.textContent = '🎀';

                const heading = document.createElement('h2');
                heading.textContent = 'You did it!';

                const scoreBox = document.createElement('div');
                scoreBox.className = 'result-score';
                scoreBox.textContent = score + ' / ' + qs.length + ' correct';

                const message = document.createElement('p');
                message.textContent = pick(
                    lines.done,
                    'Every little step counts. Keep learning! ♡'
                );

                const points = document.createElement('p');
                points.textContent =
                    'You earned ' +
                    ((score * 10) + ((qs.length - score) * 2)) +
                    ' points!';

                const again = document.createElement('button');
                again.type = 'button';
                again.className = 'next-button';
                again.textContent = '🔁 Try again';
                again.addEventListener('click', () => location.reload());

                const settings = document.createElement('a');
                settings.href = @json(route('sets.quiz', $set));
                settings.className = 'settings-button';
                settings.textContent = 'Create another quiz';

                result.append(
                    icon,
                    heading,
                    scoreBox,
                    message,
                    points,
                    again,
                    settings
                );

                game.appendChild(result);

                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 200,
                        spread: 100,
                        origin: { y: 0.6 },
                        colors: ['#fbcfe0', '#f4b0cc', '#fbcfe8']
                    });
                }
            }

            document.getElementById('next').addEventListener('click', next);

            render();
        </script>
    @endif
</body>
</html>