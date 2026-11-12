
@extends('layouts.app')

@section('title', 'Creating Flashcards | Shelly')

@section('content')

<div class="flashcard-generating-page">

    <div class="generating-card">

        <div class="generating-decoration decoration-one">✦</div>
        <div class="generating-decoration decoration-two">✧</div>

        <div id="loading-state">

            <div class="shelly-loader">
                <div class="loader-ring"></div>
                <span class="loader-star">✦</span>
            </div>

            <span class="generating-label">A LITTLE HELP FROM SHELLY</span>

            <h1>Creating your flashcards</h1>

            <p class="generating-description">
                Shelly is reading your study materials and turning
                important information into useful questions and answers.
            </p>

            <div class="progress-track">
                <div class="progress-bar"></div>
            </div>

            <p class="progress-message" id="progress-message">
                Preparing your study materials...
            </p>

            <p class="requested-count">
                Your requested amount:
                <strong>{{ $settings['count'] }} flashcards</strong>
            </p>

        </div>

        <div id="error-state" hidden>

            <div class="error-icon">!</div>

            <span class="generating-label">SOMETHING WENT WRONG</span>

            <h1>We couldn't finish creating your cards.</h1>

            <p class="generating-description" id="error-message">
                Please try again.
            </p>

            <button
                type="button"
                class="result-button"
                id="retry-button"
            >
                Try again
            </button>

            <a
                href="{{ route('sets.flashcards.setup', $set) }}"
                class="back-to-setup"
            >
                Change my settings
            </a>

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .flashcard-generating-page {
        width: 100%;
        min-height: calc(100vh - 180px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 35px 20px;
        margin: 0 auto;
        text-align: center;
    }

    .generating-card {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 620px;
        min-height: 480px;
        padding: 65px 45px;
        margin: 0 auto;
        border: 1px solid #f2dce5;
        border-radius: 28px;
        background: #ffffff;
        box-shadow: 0 15px 45px rgba(128, 75, 99, 0.07);
        text-align: center;
    }

    #loading-state,
    #error-state {
        width: 100%;
        max-width: 460px;
        margin: 0 auto;
        text-align: center;
    }

    .generating-decoration {
        position: absolute;
        color: #e9b9cc;
        opacity: .7;
        pointer-events: none;
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

    .shelly-loader {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 95px;
        height: 95px;
        margin: 0 auto 30px;
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

    .generating-label {
        display: block;
        margin-bottom: 13px;
        color: #c17e99;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2px;
        text-align: center;
    }

    .generating-card h1 {
        margin: 0 0 15px;
        color: #503c47;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(27px, 5vw, 36px);
        font-weight: 500;
        line-height: 1.3;
        text-align: center;
    }

    .generating-description {
        max-width: 440px;
        margin: 0 auto 28px;
        color: #8e7b85;
        font-size: 14px;
        line-height: 1.8;
        text-align: center;
    }

    .progress-track {
        width: 100%;
        height: 7px;
        overflow: hidden;
        margin: 30px auto 15px;
        border-radius: 20px;
        background: #f8eaf0;
    }

    .progress-bar {
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

    .progress-message {
        margin: 0;
        color: #b07b91;
        font-size: 12px;
        text-align: center;
    }

    .requested-count {
        margin: 25px 0 0;
        color: #998692;
        font-size: 12px;
        text-align: center;
    }

    .requested-count strong {
        color: #bd7895;
    }

    .error-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 75px;
        height: 75px;
        margin: 0 auto 25px;
        border-radius: 50%;
        background: #fff0f0;
        color: #bd7895;
        font-size: 35px;
    }

    .result-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 190px;
        min-height: 45px;
        margin: 8px auto 0;
        padding: 14px 24px;
        border: none;
        border-radius: 30px;
        background: #d797b0;
        color: #ffffff;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .result-button:hover {
        background: #c7809e;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .result-button:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .back-to-setup {
        display: block;
        width: fit-content;
        margin: 20px auto 0;
        color: #a77b8f;
        font-size: 12px;
        text-decoration: underline;
        text-align: center;
    }

    [hidden] {
        display: none !important;
    }

    @media (max-width: 600px) {
        .flashcard-generating-page {
            min-height: calc(100vh - 140px);
            padding: 25px 12px;
        }

        .generating-card {
            min-height: 450px;
            padding: 55px 24px;
            border-radius: 22px;
        }

        .decoration-one {
            top: 18px;
            left: 18px;
        }

        .decoration-two {
            right: 20px;
            bottom: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');

    const progressMessage = document.getElementById('progress-message');
    const errorMessage = document.getElementById('error-message');
    const retryButton = document.getElementById('retry-button');

    const generateUrl = @json(route('sets.flashcards.make', $set));
    const flashcardsUrl = @json(route('sets.flashcards', $set));
    const csrfToken = @json(csrf_token());
    const requestedCount = @json((int) $settings['count']);

    let generating = false;

    async function generateFlashcards() {

        if (generating) {
            return;
        }

        generating = true;

        loadingState.hidden = false;
        errorState.hidden = true;

        progressMessage.textContent =
            'Shelly is preparing your flashcards...';

        retryButton.disabled = true;

        try {

            const response = await fetch(generateUrl, {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },

                body: JSON.stringify({
                    count: requestedCount
                })
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message ||
                    'Shelly could not generate your flashcards.'
                );
            }

            // Automatically open the flashcard collection
            // after successful generation.
            window.location.assign(flashcardsUrl);

            return;

        } catch (error) {

            loadingState.hidden = true;
            errorState.hidden = false;

            errorMessage.textContent =
                error.message ||
                'Please check your connection and try again.';

        } finally {

            generating = false;
            retryButton.disabled = false;

        }
    }

    retryButton.addEventListener('click', generateFlashcards);

    generateFlashcards();

});
</script>
@endpush
