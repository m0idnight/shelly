
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Creating Your Summary — Shelly</title>

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

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 35px 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 15% 15%, #fceaf1 0, transparent 30%),
                radial-gradient(circle at 85% 85%, #f9e9ef 0, transparent 30%),
                var(--cream);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 510px;
        }

        .brand {
            display: block;
            width: fit-content;
            margin: 0 auto 23px;
            color: var(--pink-dark);
            font-family: 'Fredoka', sans-serif;
            font-size: 25px;
            font-weight: 600;
            text-decoration: none;
        }

        .card {
            position: relative;
            padding: 40px 34px;
            border: 1px solid var(--line);
            border-radius: 27px;
            background: white;
            box-shadow: 0 15px 45px rgba(150, 91, 117, .09);
            text-align: center;
        }

        .bow {
            margin-bottom: 15px;
            color: var(--pink);
            font-size: 35px;
            animation: float 2.5s ease-in-out infinite;
        }

        h1 {
            margin: 0;
            color: #795568;
            font-family: 'Fredoka', sans-serif;
            font-size: 29px;
            font-weight: 600;
        }

        .subtitle {
            max-width: 365px;
            margin: 12px auto 25px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .loader {
            width: 43px;
            height: 43px;
            margin: 0 auto 22px;
            border: 4px solid var(--pink-light);
            border-top-color: var(--pink);
            border-radius: 50%;
            animation: spin .9s linear infinite;
        }

        .progress-track {
            width: 100%;
            height: 8px;
            overflow: hidden;
            border-radius: 20px;
            background: var(--pink-light);
        }

        .progress-fill {
            width: 35%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #e99bb9, #c77d9c);
            animation: progress 3s ease-in-out infinite;
        }

        .status {
            min-height: 22px;
            margin: 17px 0;
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 700;
        }

        .details {
            padding: 13px 16px;
            border: 1px solid var(--line);
            border-radius: 13px;
            background: var(--pink-soft);
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .details strong {
            color: #795568;
            font-size: 12px;
        }

        .error-box {
            display: none;
            padding: 16px;
            margin-top: 20px;
            border: 1px solid #f0cbd7;
            border-radius: 13px;
            background: #fff2f5;
            color: #a75c73;
            font-size: 12px;
            line-height: 1.7;
            text-align: left;
        }

        .error-box.show {
            display: block;
        }

        .error-title {
            margin-bottom: 5px;
            font-weight: 800;
        }

        .actions {
            display: none;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .actions.show {
            display: flex;
        }

        .button {
            display: inline-flex;
            flex: 1;
            align-items: center;
            justify-content: center;
            min-height: 43px;
            padding: 11px 15px;
            border: 0;
            border-radius: 11px;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .button:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .button-primary {
            color: white;
            background: var(--pink);
        }

        .button-primary:hover {
            background: var(--pink-dark);
        }

        .button-secondary {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        .button-secondary:hover {
            background: #f9dce8;
        }

        .hidden {
            display: none !important;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes progress {
            0% { transform: translateX(-110%); }
            50% { transform: translateX(100%); }
            100% { transform: translateX(290%); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        @media (max-width: 480px) {
            .card {
                padding: 32px 20px;
            }

            h1 {
                font-size: 26px;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <main class="container">

        <a href="{{ route('dashboard') }}" class="brand">🎀 shelly</a>

        <section class="card">

            <div class="bow">🎀</div>

            <h1 id="pageTitle">Preparing your study notes!</h1>

            <p class="subtitle">
                Shelly is reading your materials and organizing
                your notes according to your preferences.
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
                @switch($settings['length'])
                    @case('quick')
                        Quick review
                        @break
                    @case('detailed')
                        Detailed notes
                        @break
                    @default
                        Balanced notes
                @endswitch
                ·
                @switch($settings['format'])
                    @case('key_points')
                        Key points
                        @break
                    @case('exam_revision')
                        Exam revision
                        @break
                    @default
                        Organized notes
                @endswitch
            </div>

            <div class="error-box" id="errorBox" role="alert">
                <div class="error-title">
                    Oh no! Something went wrong.
                </div>
                <div id="errorMessage">
                    Shelly couldn't finish creating your summary.
                </div>
            </div>

            <div class="actions" id="actions">
                <button type="button"
                        class="button button-primary"
                        id="retryButton">
                    Try again
                </button>

                <a href="{{ route('sets.summary.setup', $set) }}"
                   class="button button-secondary">
                    Back to settings
                </a>
            </div>

        </section>
    </main>

    <script>
        const generateUrl = @json(route('sets.summarize', $set));
        const summaryUrl = @json(route('sets.summary', $set));

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
            'Finding the important concepts...',
            'Organizing your study notes...',
            'Preparing clear explanations...',
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

        async function generateSummary() {
            if (generating) return;

            generating = true;

            errorBox.classList.remove('show');
            actions.classList.remove('show');

            loader.classList.remove('hidden');
            progressTrack.classList.remove('hidden');

            pageTitle.textContent = 'Preparing your study notes!';
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
                    body: JSON.stringify({})
                });

                const rawResponse = await response.text();

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

                if (!response.ok) {
                    if (response.status === 419) {
                        throw new Error(
                            'Your session has expired. Please return to summary settings and try again.'
                        );
                    }

                    if (response.status === 422) {
                        throw new Error(
                            result.message ||
                            'Your summary settings could not be processed. Please choose them again.'
                        );
                    }

                    if (response.status === 429) {
                        throw new Error(
                            'Shelly is receiving too many requests right now. Please wait a little and try again.'
                        );
                    }

                    if (response.status >= 500) {
                        throw new Error(
                            result.message ||
                            'Shelly encountered a server error while creating your notes. Please try again.'
                        );
                    }

                    throw new Error(
                        result.message ||
                        `The server returned an error (${response.status}).`
                    );
                }

                if (!rawResponse.trim()) {
                    throw new Error(
                        'Shelly received an empty response. Please try again.'
                    );
                }

                if (!result.success) {
                    throw new Error(
                        result.message ||
                        'Shelly could not finish creating your summary.'
                    );
                }

                clearInterval(statusInterval);

                statusText.textContent =
                    'Your study notes are ready! Opening them now...';

                pageTitle.textContent = 'Your summary is ready!';

                window.location.href = result.redirect || summaryUrl;

            } catch (error) {
                clearInterval(statusInterval);

                console.error('Summary generation failed:', error);

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

        retryButton.addEventListener('click', generateSummary);

        generateSummary();
    </script>
</body>
</html>
