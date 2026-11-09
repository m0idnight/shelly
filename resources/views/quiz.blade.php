<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shelly | Quiz Settings</title>

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
            max-width: 620px;
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
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
            transition: .2s;
        }

        .back-link:hover {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        .heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .bow {
            font-size: 36px;
            margin-bottom: 6px;
        }

        h1 {
            margin: 0;
            font-family: 'Fredoka', sans-serif;
            font-size: clamp(26px, 6vw, 35px);
            color: var(--pink-dark);
        }

        .subtitle {
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
        }

        .quiz-card {
            background: rgba(255,255,255,.95);
            border: 1px solid var(--line);
            border-radius: 27px;
            padding: 28px;
            box-shadow: 0 12px 35px rgba(173, 105, 137, .09);
        }

        .intro {
            margin-bottom: 24px;
            padding: 15px;
            background: var(--pink-soft);
            border: 1px dashed #eec5d6;
            border-radius: 15px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
            text-align: center;
        }

        .form-group { margin-bottom: 19px; }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 800;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 13px 14px;
            border: 1px solid var(--line);
            border-radius: 13px;
            background: var(--pink-soft);
            color: var(--ink);
            font-size: 13px;
            outline: none;
            transition: .2s;
        }

        .form-control:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(233, 155, 185, .12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 115px;
            line-height: 1.6;
        }

        .form-hint {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px;
        }

        .generate-button {
            display: block;
            width: 100%;
            margin-top: 8px;
            padding: 14px 20px;
            color: white;
            background: var(--pink);
            border: 0;
            border-radius: 30px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 5px 12px rgba(233, 155, 185, .25);
            transition: .2s;
        }

        .generate-button:hover:not(:disabled) {
            background: var(--pink-dark);
            transform: translateY(-2px);
        }

        .generate-button:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .error-message {
            padding: 12px;
            margin-bottom: 17px;
            border-radius: 12px;
            background: #fff0f0;
            color: #a94c5b;
            font-size: 13px;
        }

        .success-message {
            padding: 12px;
            margin-bottom: 17px;
            border-radius: 12px;
            background: #edf8f0;
            color: #527b5d;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            body { padding: 18px 12px 40px; }
            .quiz-card { padding: 21px 17px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>

<body>
    <main class="page">

        <nav class="topbar">
            <a href="{{ route('dashboard') }}" class="brand">🎀 shelly</a>

            <a href="{{ route('sets.show', $set) }}" class="back-link">
                ← Back to study set
            </a>
        </nav>

        <header class="heading">
            <div class="bow">🎀</div>
            <h1>Little Quiz Time!</h1>
            <p class="subtitle">{{ $set->title }}</p>
        </header>

        <section class="quiz-card">

            @if ($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <div class="intro">
                ♡ Customize your quiz before you begin.
                Choose the question format, how many questions you want,
                and what topics Shelly should focus on.
            </div>

            <form
                id="generateQuizForm"
                method="POST"
                action="{{ route('sets.quiz.prepare', $set) }}"
            >
                @csrf

                <div class="form-group">
                    <label class="form-label" for="quiz_type">
                        ♡ Choose your quiz type
                    </label>

                    <select name="quiz_type" id="quiz_type" class="form-control" required>
                        <option value="multiple_choice" {{ old('quiz_type', 'multiple_choice') === 'multiple_choice' ? 'selected' : '' }}>
                            Multiple Choice (4 options)
                        </option>

                        <option value="true_false" {{ old('quiz_type') === 'true_false' ? 'selected' : '' }}>
                            True or False
                        </option>

                        <option value="mixed" {{ old('quiz_type') === 'mixed' ? 'selected' : '' }}>
                            Mixed (Multiple Choice + True or False)
                        </option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="question_count">
                            ♡ Number of questions
                        </label>

                        <select name="question_count" id="question_count" class="form-control" required>
                            @foreach ([10, 20, 50, 100] as $count)
                                <option value="{{ $count }}" {{ (string) old('question_count', '10') === (string) $count ? 'selected' : '' }}>
                                    {{ $count }} questions
                                </option>
                            @endforeach

                            <option value="custom" {{ old('question_count') === 'custom' ? 'selected' : '' }}>
                                Custom amount
                            </option>
                        </select>
                    </div>

                    <div class="form-group" id="customCountGroup" style="display:none;">
                        <label class="form-label" for="custom_count">
                            Custom question count
                        </label>

                        <input
                            type="number"
                            id="custom_count"
                            class="form-control"
                            min="1"
                            max="100"
                            value="{{ old('custom_count', 15) }}"
                            placeholder="Enter 1–100"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="custom_instructions">
                        ♡ What should your quiz focus on?
                    </label>

                    <textarea
    name="custom_instructions"
    id="custom_instructions"
    class="form-control"
    maxlength="2000"
    style="font-family: 'DM Sans', sans-serif;"
    placeholder="Example: Focus on important definitions, concepts, and real-life examples. Make the questions moderately difficult."
>{{ old('custom_instructions') }}</textarea>
                    <span class="form-hint">
                        Optional. Type or paste specific topics, lessons, concepts, or instructions.
                    </span>
                </div>

                <button
                    type="submit"
                    class="generate-button"
                    id="generateButton"
                >
                    ✨ {{ $set->questions()->count() ? 'Generate a new quiz' : 'Make my quiz' }}
                </button>
            </form>

        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('generateQuizForm');
            const questionCount = document.getElementById('question_count');
            const customGroup = document.getElementById('customCountGroup');
            const customCount = document.getElementById('custom_count');
            const generateButton = document.getElementById('generateButton');

            function updateCustomCount() {
                const isCustom = questionCount.value === 'custom';

                customGroup.style.display = isCustom ? 'block' : 'none';
                customCount.required = isCustom;
            }

            questionCount.addEventListener('change', updateCustomCount);
            updateCustomCount();

            form.addEventListener('submit', function (event) {
                if (questionCount.value === 'custom') {
                    const amount = Number(customCount.value);

                    if (!Number.isInteger(amount) || amount < 1 || amount > 100) {
                        event.preventDefault();

                        customCount.setCustomValidity(
                            'Enter a number between 1 and 100.'
                        );

                        customCount.reportValidity();
                        return;
                    }

                    customCount.setCustomValidity('');
                    questionCount.value = customCount.value;
                }

                generateButton.disabled = true;
                generateButton.textContent = 'Opening quiz generator...';
            });
        });
    </script>
</body>
</html>