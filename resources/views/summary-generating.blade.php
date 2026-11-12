
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Creating Your Summary — Shelly</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #d797b0;
            --pink-dark: #a85e7d;
            --pink-light: #fff0f6;
            --pink-soft: #fff8fb;
            --cream: #fffdfb;
            --ink: #604b56;
            --muted: #a58c98;
            --line: #f2dce5;
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
            max-width: 620px;
        }

        .card {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding: 65px 45px;
            border: 1px solid var(--line);
            border-radius: 28px;
            background: #ffffff;
            box-shadow: 0 15px 45px rgba(128, 75, 99, 0.07);
            text-align: center;
        }

        /* Decorations */

        .generating-decoration {
            position: absolute;
            color: #e9b9cc;
            opacity: .7;
        }

        .decoration-one {
            top: 25px;
            left: 30px;
            font-size: 25px;
        }

        .decoration-two {
            right: 35px;
            bottom: 30px;
            font-size: 30px;
        }

        /* Loader */

        .shelly-loader {
            position: relative;
            width: 95px;
            height: 95px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader-ring {
            position: absolute;
            inset: 0;
            border: 4px solid #f8e7ee;
            border-top-color: #d78eaa;
            border-right-color: #d78eaa;
            border-radius: 50%;
            animation: shelly-spin 1.4s linear infinite;
        }

        .loader-star {
            color: #ce86a2;
            font-size: 35px;
            animation: shelly-pulse 1.5s ease-in-out infinite;
        }

        @keyframes shelly-spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes shelly-pulse {
            50% {
                transform: scale(1.2);
                opacity: .65;
            }
        }

        /* Heading */

        .generating-label {
            display: block;
            margin-bottom: 13px;
            color: #c17e99;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        h1 {
            margin: 0 0 15px;
            color: #503c47;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(27px, 5vw, 36px);
            font-weight: 500;
        }

        .subtitle {
            max-width: 440px;
            margin: 0 auto 28px;
            color: #8e7b85;
            font-size: 14px;
            line-height: 1.8;
        }

        /* Progress */

        .progress-track {
            width: 100%;
            height: 7px;
            overflow: hidden;
            margin: 30px 0 15px;
            border-radius: 20px;
            background: #f8eaf0;
        }

        .progress-fill {
            width: 35%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #d99ab3, #edc1d2);
            animation: shelly-progress 2s ease-in-out infinite alternate;
        }

        @keyframes shelly-progress {
            from {
                transform: translateX(-35%);
            }

            to {
                transform: translateX(190%);
            }
        }

        .status {
            min-height: 22px;
            margin: 17px 0;
            color: #b07b91;
            font-size: 12px;
        }

        /* Selected settings */

        .details {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 25px;
            padding: 17px;
            border: 1px solid #f2e3e9;
            border-radius: 14px;
            background: #fff9fb;
            text-align: left;
        }

        .material-title {
            padding-bottom: 12px;
            border-bottom: 1px solid #f0e2e7;
        }

        .detail-label {
            display: block;
            margin-bottom: 5px;
            color: #b18c9c;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .material-title strong {
            display: block;
            color: #795568;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .settings-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .setting-item strong {
            color: #bd7895;
            font-size: 12px;
            font-weight: 700;
        }

        /* Error */

        .error-icon {
            width: 75px;
            height: 75px;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 35px;
            color: #bd7895;
            background: #fff0f0;
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

        /* Actions */

        .actions {
            display: none;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .actions.show {
            display: flex;
        }

        .button {
            display: inline-flex;
            flex: 1;
            align-items: center;
            justify-content: center;
            min-height: 45px;
            padding: 12px 18px;
            border: none;
            border-radius: 30px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        .button:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .button-primary {
            color: #ffffff;
            background: #d797b0;
        }

        .button-primary:hover {
            background: #c7809e;
            transform: translateY(-2px);
        }

        .button-secondary {
            color: #a77b8f;
            background: #fff0f6;
        }

        .button-secondary:hover {
            background: #f9dce8;
        }

        [hidden],
        .hidden {
            display: none !important;
        }

        /* Responsive */

        @media (max-width: 600px) {
            body {
                padding: 25px 15px;
            }

            .card {
                padding: 55px 24px;
            }

            h1 {
                font-size: 28px;
            }
        }

        @media (max-width: 420px) {
            .settings-row {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body>

    <main class="container">

        <section class="card">

            <div class="generating-decoration decoration-one">✦</div>
            <div class="generating-decoration decoration-two">✧</div>

            <!-- LOADING STATE -->
            <div id="loading-state">

                <div class="shelly-loader">
                    <div class="loader-ring"></div>
                    <span class="loader-star">✦</span>
                </div>

                <span class="generating-label">
                    A LITTLE HELP FROM SHELLY
                </span>

                <h1 id="pageTitle">
                    Creating your study notes
                </h1>

                <p class="subtitle">
                    Shelly is reading your study materials and organizing
                    important concepts, explanations, and information
                    into clear study notes.
                </p>

                <div class="progress-track" id="progressTrack">
                    <div class="progress-fill"></div>
                </div>

                <p class="status" id="status" aria-live="polite">
                    Preparing your study materials...
                </p>

                <div class="details">

                    <div class="material-title">
                        <span class="detail-label">STUDY MATERIAL</span>
                        <strong>{{ $set->title }}</strong>
                    </div>

                    <div class="settings-row">

                        <div class="setting-item">
                            <span class="detail-label">SUMMARY LENGTH</span>

                            <strong>
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
                            </strong>
                        </div>

                        <div class="setting-item">
                            <span class="detail-label">NOTE FORMAT</span>

                            <strong>
                                @switch($settings['format'])
                                    @case('key_points')
                                        Key points
                                        @break
                                    @case('exam_revision')
                                        Exam revision
                                        @break
                                    @case('table')
                                        Table format
                                        @break
                                    @default
                                        Organized notes
                                @endswitch
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

            <!-- ERROR STATE -->
            <div id="error-state" hidden>

                <div class="error-icon">!</div>

                <span class="generating-label">
                    SOMETHING WENT WRONG
                </span>

                <h1>Let's try that again.</h1>

                <p class="subtitle">
                    Shelly couldn't finish creating your study notes.
                    You can try again or return to your settings.
                </p>

                <div class="error-box show" id="errorBox" role="alert">
                    <div class="error-title">
                        Oh no! Something went wrong.
                    </div>

                    <div id="errorMessage">
                        Shelly couldn't finish creating your summary.
                    </div>
                </div>

                <div class="actions show">

                    <button
                        type="button"
                        class="button button-primary"
                        id="retryButton"
                    >
                        Try again
                    </button>

                    <a
                        href="{{ route('sets.summary.setup', $set) }}"
                        class="button button-secondary"
                    >
                        Back to settings
                    </a>

                </div>

            </div>

        </section>

    </main>

    <script>
        const generateUrl = @json(route('sets.summarize', $set));
        const summaryUrl = @json(route('sets.summary', $set));

        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        ).content;

        const loadingState = document.getElementById('loading-state');
        const errorState = document.getElementById('error-state');

        const statusText = document.getElementById('status');
        const errorMessage = document.getElementById('errorMessage');

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

            loadingState.hidden = false;
            errorState.hidden = true;

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

                window.location.assign(result.redirect || summaryUrl);

            } catch (error) {

                clearInterval(statusInterval);

                console.error('Summary generation failed:', error);

                generating = false;

                loadingState.hidden = true;
                errorState.hidden = false;

                errorMessage.textContent = error.message;

                retryButton.disabled = false;
                retryButton.textContent = 'Try again';

            }

        }

        retryButton.addEventListener('click', generateSummary);

        generateSummary();
    </script>

</body>
</html>
