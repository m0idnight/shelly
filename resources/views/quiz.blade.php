<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz</title>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <style>
        body { font-family: sans-serif; max-width: 520px; margin: 0 auto; padding: 16px; }
        .opt { display: block; width: 100%; text-align: left; padding: 14px; margin: 8px 0; font-size: 16px;
               border: 2px solid #f9a8d4; border-radius: 12px; background: #fff0f6; cursor: pointer; }
        .opt.right { background: #bbf7d0; border-color: #22c55e; }
        .opt.wrong { background: #fecaca; border-color: #ef4444; }
        #feedback { margin: 14px 0; padding: 14px; border-radius: 12px; background: #fce7f3; }
        #next { padding: 10px 20px; font-size: 16px; }
        .hidden { display: none; }

        #popup { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) scale(0.5); opacity: 0;
                 padding: 14px 22px; border-radius: 999px; background: #f472b6; color: white; font-size: 18px;
                 font-weight: bold; box-shadow: 0 6px 20px rgba(244,114,182,0.5); z-index: 999;
                 pointer-events: none; text-align: center; max-width: 90%; }
        #popup.show { animation: pop 2s ease forwards; }
        #popup.sad { background: #c084fc; box-shadow: 0 6px 20px rgba(192,132,252,0.5); }
        @keyframes pop {
            0% { transform: translateX(-50%) scale(0.5); opacity: 0; }
            15% { transform: translateX(-50%) scale(1.1); opacity: 1; }
            25% { transform: translateX(-50%) scale(1); opacity: 1; }
            85% { transform: translateX(-50%) scale(1); opacity: 1; }
            100% { transform: translateX(-50%) scale(0.9); opacity: 0; }
        }
    </style>
</head>
<body>
    <div id="popup"></div>

    <a href="/sets/{{ $set->id }}">← Back</a>
    <h1>🎀 Quiz: {{ $set->title }}</h1>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/sets/{{ $set->id }}/quiz/generate">
        @csrf
        <button type="submit">✨ {{ $set->questions->count() ? 'Re-make' : 'Make' }} quiz</button>
    </form>

    @if ($set->questions->count())
        <div id="game">
            <p><span id="progress"></span> · ⭐ Score: <span id="score">0</span> · 🔥 Streak: <span id="streak">0</span></p>
            <h2 id="question"></h2>
            <div id="options"></div>
            <div id="feedback" class="hidden"></div>
            <button id="next" class="hidden" onclick="next()">Next →</button>
        </div>

        <script>
            const qs = @json($questions);
            const lines = @json(config('shelly'));
            let i = 0, score = 0, streak = 0, answered = false;
            let audioCtx = null;

            function pick(list) { return list[Math.floor(Math.random() * list.length)]; }

            function tone(freq, start, duration, type) {
                const o = audioCtx.createOscillator();
                const g = audioCtx.createGain();
                o.type = type;
                o.frequency.value = freq;
                g.gain.setValueAtTime(0.25, audioCtx.currentTime + start);
                g.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + start + duration);
                o.connect(g);
                g.connect(audioCtx.destination);
                o.start(audioCtx.currentTime + start);
                o.stop(audioCtx.currentTime + start + duration);
            }

            function playDing() {
                audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                tone(880, 0, 0.25, 'sine');
                tone(1320, 0.12, 0.4, 'sine');
            }

            function playBuzz() {
                audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                tone(160, 0, 0.35, 'sawtooth');
            }

            function showPopup(message, sad) {
                const p = document.getElementById('popup');
                p.textContent = message;
                p.className = '';
                void p.offsetWidth;
                p.className = 'show' + (sad ? ' sad' : '');
            }

            function render() {
                answered = false;
                const q = qs[i];
                document.getElementById('progress').textContent = 'Question ' + (i + 1) + '/' + qs.length;
                document.getElementById('question').textContent = q.question;
                document.getElementById('feedback').classList.add('hidden');
                document.getElementById('next').classList.add('hidden');

                const box = document.getElementById('options');
                box.innerHTML = '';
                q.options.forEach((text, idx) => {
                    const b = document.createElement('button');
                    b.className = 'opt';
                    b.textContent = text;
                    b.onclick = () => choose(idx);
                    box.appendChild(b);
                });
            }

            function choose(idx) {
                if (answered) return;
                answered = true;

                const q = qs[i];
                const buttons = document.querySelectorAll('.opt');
                const correct = idx === q.correct_index;

                buttons[q.correct_index].classList.add('right');

                if (correct) {
                    score++;
                    streak++;
                    playDing();
                    confetti({ particleCount: 90, spread: 70, origin: { y: 0.7 }, colors: ['#f9a8d4', '#f472b6', '#fbcfe8', '#ffffff'] });
                    showPopup((streak === 3 || streak === 5) ? pick(lines.streak) : pick(lines.correct), false);
                } else {
                    streak = 0;
                    buttons[idx].classList.add('wrong');
                    playBuzz();
                    showPopup(pick(lines.wrong), true);
                }

                document.getElementById('score').textContent = score;
                document.getElementById('streak').textContent = streak;

                const fb = document.getElementById('feedback');
                fb.innerHTML = '';
                const a = document.createElement('p');
                a.textContent = '✅ Correct answer: ' + q.options[q.correct_index];
                const why = document.createElement('p');
                why.textContent = '💡 Why: ' + q.explanation;
                fb.append(a, why);
                fb.classList.remove('hidden');

                const nextBtn = document.getElementById('next');
                nextBtn.textContent = (i === qs.length - 1) ? 'See results 🎉' : 'Next →';
                nextBtn.classList.remove('hidden');
            }

            function next() {
                i++;
                if (i >= qs.length) { finish(); } else { render(); }
            }

            function finish() {
                const game = document.getElementById('game');
                game.innerHTML = '';
                const h = document.createElement('h2');
                h.textContent = '🎉 You got ' + score + ' out of ' + qs.length + '!';
                const p = document.createElement('p');
                p.textContent = pick(lines.done);
                const again = document.createElement('button');
                again.textContent = '🔁 Try again';
                again.onclick = () => location.reload();
                game.append(h, p, again);
                confetti({ particleCount: 200, spread: 100, origin: { y: 0.6 }, colors: ['#f9a8d4', '#f472b6', '#fbcfe8'] });
            }

            render();
        </script>
    @endif
</body>
</html>