<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shelly | Preparing Your Quiz</title>

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
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 25px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 10%, #ffe8f1 0, transparent 28%),
                radial-gradient(circle at 90% 85%, #f9e8f3 0, transparent 30%),
                var(--cream);
        }

        .container {
            width: 100%;
            max-width: 510px;
            text-align: center;
        }

        .brand {
            display: inline-block;
            margin-bottom: 28px;
            color: var(--pink-dark);
            font-family: 'Fredoka', sans-serif;
            font-size: 27px;
            font-weight: 600;
            text-decoration: none;
        }

        .card {
            padding: 40px 30px;
            background: rgba(255,255,255,.95);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: 0 12px 35px rgba(173, 105, 137, .09);
        }

        .bow {
            font-size: 48px;
            margin-bottom: 14px;
            animation: float 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        h1 {
            margin: 0;
            color: var(--pink-dark);
            font-family: 'Fredoka', sans-serif;
            font-size: 30px;
        }

        .subtitle {
            margin: 10px 0 25px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .loader {
            width: 58px;
            height: 58px;
            margin: 0 auto 22px;
            border: 5px solid #f8e4ed;
            border-top-color: var(--pink);
            border-right-color: var(--pink-dark);
            border-radius: 50%;
            animation: spin .9s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .progress-track {
            width: 100%;
            height: 9px;
            margin: 20px 0 12px;
            overflow: hidden;
            background: #f9e7ef;
            border-radius: 30px;
        }

        .progress-fill {
            width: 25%;
            height: 100%;
            background: linear-gradient(90deg, #f3b5ce, #d88aaa);
            border-radius: inherit;
            animation: progress 3s ease-in-out infinite alternate;
        }

        @keyframes progress {
            from { width: 20%; }
            to { width: 85%; }
        }

        .status {
            min-height: 24px;
            color: var(--pink-dark);
            font-size: 13px;
            font-weight: 700;
        }

        .details {
            margin-top: 20px;
            padding: 15px;
            border: 1px dashed #eec5d6;
            border-radius: 15px;
            background: var(--pink-soft);
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .error-box {
            display: none;
            margin-top: 20px;
            padding: 16px;
            border: 1px solid #f0c7ce;
            border-radius: 15px;
            background: #fff0f0;
            color: #a94c5b;
            font-size: 13px;
            line-height: 1.7;
            text-align: left;
        }

        .error-box.show { display: block; }

        .error-title {
            margin-bottom: 5px;
            font-weight: 800;
        }

        .actions {
            display: none;
            gap: 10px;
            margin-top: 20px;
        }

        .actions.show { display: flex; }

        .button {
            flex: 1;
            padding: 12px 15px;
            border: 0;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .button-primary {
            color: white;
            background: var(--pink);
        }

        .button-primary:hover { background: var(--pink-dark); }

        .button-secondary {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        .button-secondary:hover { background: #f9dce8; }

        .hidden { display: none !important; }

        @media (max-width: 480px) {
            .card { padding: 32px 20px; }
            h1 { font-size: 26px; }
            .actions { flex-direction: column; }
        }
    </style>
</head>

<body>
    <main class="container">

        <a href="{{ route('dashboard') }}" class="brand">🎀 shelly</a>

        <section class="card">

            <div class="bow">🎀</div>

            <h1 id="pageTitle">Preparing your quiz!</h1>

            <p class="subtitle">
                Shelly is reading your study materials and creating questions just for your practice.
            </p>

            <div class="loader" id="loader"></div>

            <div class="progress-track" id="progressTrack">
                <div class="progress-fill"></div>
            </div>

            <p class="status" id="status" aria-live="polite">
                Getting everything ready...
            </p>

            <div class="details">
                <strong>{{ $set->title }}</strong>
                <br>
                {{ $settings['question_count'] }} questions
                ·
                @switch($settings['quiz_type'])
                    @case('true_false')
                        True or False
                        @break
                    @case('mixed')
                        Mixed Quiz
                        @break
                    @default
                        Multiple Choice
                @endswitch
            </div>

            <div class="error-box" id="errorBox" role="alert">
                <div class="error-title">Oh no! Something went wrong.</div>
                <div id="errorMessage">
                    Shelly couldn't finish creating your quiz.
                </div>
            </div>

            <div class="actions" id="actions">
                <button type="button" class="button button-primary" id="retryButton">
                    Try again
                </button>

                <a href="{{ route('sets.quiz', $set) }}" class="button button-secondary">
                    Back to settings
                </a>
            </div>

        </section>
    </main>

    <script>
const generateUrl = @json(route('sets.quiz.make', $set));
const quizUrl = @json(route('sets.quiz.play', $set));
const settings = @json($settings);

const csrfToken = document.querySelector(
    'meta[name="csrf-token"]'
).content;

const statusText = document.getElementById('status');
const errorBox = document.getElementById('errorBox');
const errorMessage = document.getElementById('errorMessage');
const actions = document.getElementById('actions');
const loader = document.getElementById('loader');
const progressTrack = document.getElementById('progressTrack');
const pageTitle = document.getElementById('pageTitle');
const retryButton = document.getElementById('retryButton');

let generating = false;
let statusInterval = null;

const messages = [
    'Shelly is reading your materials...',
    'Finding important topics...',
    'Preparing your questions...',
    'Checking your answer choices...',
    'Almost ready!'
];

function startStatusMessages() {
    let index = 0;

    clearInterval(statusInterval);

    statusInterval = setInterval(() => {
        index = (index + 1) % messages.length;
        statusText.textContent = messages[index];
    }, 3500);
}

async function generateQuiz() {
    if (generating) return;

    generating = true;

    errorBox.classList.remove('show');
    actions.classList.remove('show');

    loader.classList.remove('hidden');
    progressTrack.classList.remove('hidden');

    pageTitle.textContent = 'Preparing your quiz!';
    statusText.textContent = messages[0];

    retryButton.disabled = true;
    retryButton.textContent = 'Generating...';

    startStatusMessages();

    try {
        const response = await fetch(generateUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(settings)
        });

        // Read the response as text first.
        const rawResponse = await response.text();

        // Safely attempt to decode JSON.
        let result = {};

        try {
            result = rawResponse.trim()
                ? JSON.parse(rawResponse)
                : {};
        } catch (parseError) {
            console.error(
                'Invalid server response:',
                rawResponse
            );
        }

        // Handle unsuccessful HTTP responses.
        if (!response.ok) {
            if (response.status === 419) {
                throw new Error(
                    'Your session has expired. Please return to quiz settings and try again.'
                );
            }

            if (response.status === 422) {
                throw new Error(
                    result.message ||
                    'Some quiz settings could not be processed. Please check your selections.'
                );
            }

            if (response.status === 429) {
                throw new Error(
                    'Shelly is receiving too many requests right now. Please wait a little and try again.'
                );
            }

            if (response.status >= 500) {
                throw new Error(
                    'Shelly encountered a server error while creating your quiz. Please try again. If this continues, check the Laravel error log.'
                );
            }

            throw new Error(
                result.message ||
                `The server returned an error (${response.status}).`
            );
        }

        // Handle an empty or unexpected successful response.
        if (!rawResponse.trim()) {
            throw new Error(
                'Shelly received an empty response from the server. Please try again.'
            );
        }

        if (!result.success) {
            throw new Error(
                result.message ||
                'Shelly could not finish creating your quiz.'
            );
        }

        clearInterval(statusInterval);

        statusText.textContent =
            'Your quiz is ready! Opening it now...';

        pageTitle.textContent = 'Your quiz is ready!';

        window.location.href = result.redirect || quizUrl;

    } catch (error) {
        clearInterval(statusInterval);

        console.error('Quiz generation failed:', error);

        generating = false;

        loader.classList.add('hidden');
        progressTrack.classList.add('hidden');

        pageTitle.textContent = 'Let’s try that again!';
        statusText.textContent = 'Shelly needs a little help.';

        errorMessage.textContent = error.message;

        errorBox.classList.add('show');
        actions.classList.add('show');

        retryButton.disabled = false;
        retryButton.textContent = 'Try again';
    }
}

retryButton.addEventListener('click', generateQuiz);

generateQuiz();
</script>
</body>
</html>