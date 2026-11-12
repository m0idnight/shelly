 @extends('layouts.app')

@section('title', 'Quiz Setup | Shelly')

@section('content')

<div class="quiz-setup-page">

    <div class="setup-heading">
        <div>
            <span class="page-kicker">LET'S PREPARE YOUR QUIZ</span>
            <h1 class="page-title">Quiz Setup</h1>
            <p class="page-description">
                Choose your quiz style and how many questions Shelly should prepare.
            </p>
        </div>

        <div class="heading-decoration">୨୧</div>
    </div>

    <div class="material-panel">
        <div class="material-icon">✧</div>

        <div class="material-details">
            <span class="material-label">CREATING A QUIZ FOR</span>
            <h2>{{ $set->title }}</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="setup-error">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('error'))
        <div class="setup-error">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="setup-success">
            {{ session('success') }}
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('sets.quiz.prepare', $set) }}"
        class="setup-form"
        id="generateQuizForm"
    >
        @csrf

        {{-- QUIZ TYPE --}}
        <section class="setup-section">

            <div class="section-heading">
                <span class="section-number">01</span>

                <div>
                    <h2>What type of quiz would you like?</h2>
                    <p>
                        Pick the question format that works best for your study session.
                    </p>
                </div>
            </div>

            <div class="quiz-type-grid">

                <label class="quiz-type-option">
                    <input
                        type="radio"
                        name="quiz_type"
                        value="multiple_choice"
                        {{ old('quiz_type', 'multiple_choice') === 'multiple_choice' ? 'checked' : '' }}
                        required
                    >

                    <span class="quiz-type-content">
                        <span class="type-icon">A B C</span>
                        <strong>Multiple Choice</strong>
                        <small>Choose the correct answer from four options.</small>
                    </span>
                </label>

                <label class="quiz-type-option">
                    <input
                        type="radio"
                        name="quiz_type"
                        value="true_false"
                        {{ old('quiz_type') === 'true_false' ? 'checked' : '' }}
                    >

                    <span class="quiz-type-content">
                        <span class="type-icon">T / F</span>
                        <strong>True or False</strong>
                        <small>Decide whether each statement is correct.</small>
                    </span>
                </label>

                <label class="quiz-type-option">
                    <input
                        type="radio"
                        name="quiz_type"
                        value="mixed"
                        {{ old('quiz_type') === 'mixed' ? 'checked' : '' }}
                    >

                    <span class="quiz-type-content">
                        <span class="type-icon">A + T</span>
                        <strong>Mixed Quiz</strong>
                        <small>Practise with both question formats.</small>
                    </span>
                </label>

            </div>
        </section>

        {{-- QUESTION QUANTITY --}}
        <section class="setup-section">

            <div class="section-heading">
                <span class="section-number">02</span>

                <div>
                    <h2>How many questions do you need?</h2>
                    <p>
                        Choose a quantity that suits your study session.
                    </p>
                </div>
            </div>

            <div class="quantity-grid">

                @foreach ([10, 20, 50, 100] as $quantity)
                    <label class="quantity-option">
                        <input
                            type="radio"
                            name="question_quantity_preset"
                            value="{{ $quantity }}"
                            {{ (int) old('question_count', 10) === $quantity ? 'checked' : '' }}
                        >

                        <span class="quantity-content">
                            <strong>{{ $quantity }}</strong>
                            <small>questions</small>
                        </span>
                    </label>
                @endforeach

            </div>

            <div class="custom-quantity">

                <label for="custom_count">
                    Or enter a custom quantity
                </label>

                <div class="custom-input-wrap">
                    <input
                        type="number"
                        id="custom_count"
                        min="1"
                        max="100"
                        value="{{ !in_array((int) old('question_count', 10), [10, 20, 50, 100]) ? old('question_count') : '' }}"
                        placeholder="Enter 1–100"
                    >

                    <span>questions</span>
                </div>

                <p class="input-hint">
                    Choose between 1 and 100 questions.
                </p>

                <input
                    type="hidden"
                    name="question_count"
                    id="question_count"
                    value="{{ old('question_count', 10) }}"
                >

            </div>
        </section>

        {{-- CUSTOM INSTRUCTIONS --}}
        <section class="setup-section">

            <div class="section-heading">
                <span class="section-number">03</span>

                <div>
                    <h2>What should your quiz focus on?</h2>
                    <p>
                        Give Shelly a little direction for your practice session.
                    </p>
                </div>
            </div>

            <div class="instructions-group">

                <label for="custom_instructions">
                    YOUR QUIZ INSTRUCTIONS
                </label>

                <textarea
                    name="custom_instructions"
                    id="custom_instructions"
                    maxlength="2000"
                    placeholder="Example: Focus on important definitions, key concepts, and real-life examples. Make the questions moderately difficult."
                >{{ old('custom_instructions') }}</textarea>

                <p class="input-hint">
                    Optional. Enter specific topics, lessons, concepts, or instructions.
                </p>

            </div>
        </section>

        {{-- NOTE --}}
        <div class="generation-note">
            <span class="note-icon">✦</span>

            <div>
                <strong>A little note from Shelly</strong>
                <p>
                    Shelly will use your uploaded study materials to create
                    questions based on your selected format and quantity.
                    Questions will focus on the information available in your materials.
                </p>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="setup-actions">

            <a
                href="{{ route('sets.show', $set) }}"
                class="back-link"
            >
                ← Back to study set
            </a>

            <button
                type="submit"
                class="generate-button"
                id="generateButton"
            >
                {{ $set->questions()->count() ? 'Generate a new quiz' : 'Create my quiz' }}
                <span>→</span>
            </button>

        </div>

    </form>

    <p class="bottom-note">
        Your study session, your pace.
    </p>

</div>

@endsection

@push('styles')
<style>
.quiz-setup-page {
    max-width: 850px;
    margin: 0 auto;
    padding-bottom: 35px;
}

.quiz-setup-page .setup-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.quiz-setup-page .page-title {
    margin: 7px 0;
}

.quiz-setup-page .page-kicker {
    color: #b97891;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
}

.quiz-setup-page .page-description {
    margin: 0;
    color: #a18c96;
    font-size: 13px;
    line-height: 1.7;
}

.quiz-setup-page .heading-decoration {
    display: grid;
    place-items: center;
    width: 62px;
    height: 62px;
    flex-shrink: 0;
    border: 1px solid #efd4df;
    border-radius: 50%;
    background: #fff1f6;
    color: #cf91aa;
    font-size: 27px;
}

.quiz-setup-page .material-panel {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 20px;
    margin-bottom: 20px;
    border: 1px solid #efdde4;
    border-radius: 15px;
    background: #fffdfd;
    box-shadow: 0 8px 25px rgba(151, 95, 115, .05);
}

.quiz-setup-page .material-icon {
    display: grid;
    place-items: center;
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    border-radius: 14px;
    background: #fff0f5;
    color: #c47e9b;
    font-size: 22px;
}

.quiz-setup-page .material-label {
    color: #b98a9d;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .13em;
}

.quiz-setup-page .material-details h2 {
    margin: 4px 0 0;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 17px;
    overflow-wrap: anywhere;
}

.quiz-setup-page .setup-error,
.quiz-setup-page .setup-success {
    padding: 13px 16px;
    margin-bottom: 18px;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.6;
}

.quiz-setup-page .setup-error {
    border: 1px solid #f0cbd7;
    background: #fff2f5;
    color: #a75c73;
}

.quiz-setup-page .setup-success {
    border: 1px solid #d7eadb;
    background: #f1f8f2;
    color: #527b5d;
}

.quiz-setup-page .setup-form {
    display: grid;
    gap: 18px;
}

.quiz-setup-page .setup-section {
    padding: 26px;
    border: 1px solid #efdde4;
    border-radius: 17px;
    background: #fffdfd;
    box-shadow: 0 8px 25px rgba(151, 95, 115, .05);
}

.quiz-setup-page .section-heading {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 24px;
}

.quiz-setup-page .section-number {
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 11px;
    background: #fff0f5;
    color: #b16f8a;
    font-size: 12px;
    font-weight: 900;
}

.quiz-setup-page .section-heading h2 {
    margin: 2px 0 5px;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 19px;
}

.quiz-setup-page .section-heading p {
    margin: 0;
    color: #a18c96;
    font-size: 12px;
    line-height: 1.6;
}

/* QUIZ TYPE CARDS */

.quiz-setup-page .quiz-type-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}

.quiz-setup-page .quiz-type-option {
    position: relative;
    cursor: pointer;
}

.quiz-setup-page .quiz-type-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.quiz-setup-page .quiz-type-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 165px;
    padding: 19px 12px;
    border: 1px solid #f0e2e7;
    border-radius: 15px;
    background: #fffdfd;
    text-align: center;
    transition: .2s ease;
}

.quiz-setup-page .type-icon {
    display: grid;
    place-items: center;
    min-width: 52px;
    height: 43px;
    padding: 0 10px;
    margin-bottom: 13px;
    border-radius: 12px;
    background: #fff0f5;
    color: #b16f8a;
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 1px;
}

.quiz-setup-page .quiz-type-content strong {
    color: #795568;
    font-size: 12px;
    line-height: 1.5;
}

.quiz-setup-page .quiz-type-content small {
    margin-top: 7px;
    color: #a18c96;
    font-size: 10px;
    line-height: 1.6;
}

.quiz-setup-page .quiz-type-option input:checked + .quiz-type-content {
    border-color: #d99bb0;
    background: #fff3f7;
    box-shadow: 0 0 0 2px #f7e3eb;
}

.quiz-setup-page .quiz-type-option input:checked + .quiz-type-content .type-icon {
    background: #f6dce7;
    color: #995b76;
}

.quiz-setup-page .quiz-type-option input:focus-visible + .quiz-type-content {
    outline: 3px solid #d99bb0;
    outline-offset: 3px;
}

/* QUESTION QUANTITY */

.quiz-setup-page .quantity-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}

.quiz-setup-page .quantity-option {
    position: relative;
    cursor: pointer;
}

.quiz-setup-page .quantity-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.quiz-setup-page .quantity-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 112px;
    border: 1px solid #f0e2e7;
    border-radius: 15px;
    background: #fffdfd;
    transition: .2s ease;
}

.quiz-setup-page .quantity-content strong {
    color: #795568;
    font-family: Georgia, serif;
    font-size: 29px;
}

.quiz-setup-page .quantity-content small {
    margin-top: 3px;
    color: #a18c96;
    font-size: 11px;
}

.quiz-setup-page .quantity-option input:checked + .quantity-content {
    border-color: #d99bb0;
    background: #fff3f7;
    box-shadow: 0 0 0 2px #f7e3eb;
}

.quiz-setup-page .quantity-option input:focus-visible + .quantity-content {
    outline: 3px solid #d99bb0;
    outline-offset: 3px;
}

.quiz-setup-page .custom-quantity {
    margin-top: 26px;
}

.quiz-setup-page .custom-quantity > label {
    display: block;
    margin-bottom: 10px;
    color: #795568;
    font-size: 12px;
    font-weight: 800;
}

.quiz-setup-page .custom-input-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.quiz-setup-page .custom-input-wrap input {
    width: 180px;
    max-width: 60%;
    padding: 12px 14px;
    border: 1px solid #ecdce3;
    border-radius: 11px;
    background: #fffdfd;
    color: #755d69;
    font: inherit;
    font-size: 13px;
}

.quiz-setup-page .custom-input-wrap input:focus {
    outline: none;
    border-color: #d99bb0;
    box-shadow: 0 0 0 3px #fff0f5;
}

.quiz-setup-page .custom-input-wrap > span {
    color: #a18c96;
    font-size: 12px;
}

.quiz-setup-page .input-hint {
    margin: 7px 0 0;
    color: #a18c96;
    font-size: 10px;
    line-height: 1.6;
}

/* CUSTOM INSTRUCTIONS */

.quiz-setup-page .instructions-group > label {
    display: block;
    margin-bottom: 10px;
    color: #795568;
    font-size: 12px;
    font-weight: 800;
}

.quiz-setup-page .instructions-group textarea {
    display: block;
    width: 100%;
    min-height: 125px;
    padding: 13px 14px;
    border: 1px solid #ecdce3;
    border-radius: 11px;
    background: #fffdfd;
    color: #755d69;
    font-family: inherit;
    font-size: 13px;
    line-height: 1.7;
    resize: vertical;
    transition: .2s ease;
}

.quiz-setup-page .instructions-group textarea:focus {
    outline: none;
    border-color: #d99bb0;
    box-shadow: 0 0 0 3px #fff0f5;
}

/* NOTE */

.quiz-setup-page .generation-note {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 18px;
    border: 1px solid #f0dce4;
    border-radius: 14px;
    background: #fff5f8;
}

.quiz-setup-page .note-icon {
    color: #c47e9b;
    font-size: 20px;
}

.quiz-setup-page .generation-note strong {
    color: #795568;
    font-size: 12px;
}

.quiz-setup-page .generation-note p {
    margin: 5px 0 0;
    color: #a18c96;
    font-size: 11px;
    line-height: 1.7;
}

/* ACTIONS */

.quiz-setup-page .setup-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-top: 5px;
}

.quiz-setup-page .back-link {
    color: #a16f86;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
}

.quiz-setup-page .back-link:hover {
    text-decoration: underline;
}

.quiz-setup-page .generate-button {
    padding: 13px 22px;
    border: 0;
    border-radius: 12px;
    background: #d98eaa;
    color: white;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.quiz-setup-page .generate-button:hover:not(:disabled) {
    background: #bd7894;
    transform: translateY(-1px);
}

.quiz-setup-page .generate-button:disabled {
    opacity: .7;
    cursor: not-allowed;
}

.quiz-setup-page .generate-button span {
    margin-left: 6px;
}

.quiz-setup-page .bottom-note {
    margin: 22px 0 8px;
    color: #a17e8e;
    font-family: Georgia, serif;
    font-size: 12px;
    font-style: italic;
    text-align: center;
}

/* RESPONSIVE */

@media (max-width: 650px) {
    .quiz-setup-page .quiz-type-grid {
        grid-template-columns: 1fr;
    }

    .quiz-setup-page .quiz-type-content {
        min-height: 120px;
    }

    .quiz-setup-page .quantity-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .quiz-setup-page .setup-section {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .quiz-setup-page .setup-heading {
        align-items: flex-start;
    }

    .quiz-setup-page .heading-decoration {
        width: 48px;
        height: 48px;
        font-size: 22px;
    }

    .quiz-setup-page .setup-actions {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .quiz-setup-page .generate-button {
        width: 100%;
    }

    .quiz-setup-page .back-link {
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('generateQuizForm');
    const customCount = document.getElementById('custom_count');
    const questionCount = document.getElementById('question_count');
    const presetInputs = document.querySelectorAll(
        '.quiz-setup-page input[name="question_quantity_preset"]'
    );
    const generateButton = document.getElementById('generateButton');

    if (!form || !customCount || !questionCount) {
        return;
    }

    // Update the submitted quantity when a preset is selected.
    presetInputs.forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (this.checked) {
                questionCount.value = this.value;
                customCount.value = '';
                customCount.setCustomValidity('');
            }
        });
    });

    // Clear preset selection when the user enters a custom quantity.
    customCount.addEventListener('input', function () {
        presetInputs.forEach(function (radio) {
            radio.checked = false;
        });

        customCount.setCustomValidity('');

        if (this.value !== '') {
            const amount = Number(this.value);

            if (Number.isInteger(amount) && amount >= 1 && amount <= 100) {
                questionCount.value = amount;
            } else {
                questionCount.value = '';
            }
        } else {
            questionCount.value = '';
        }
    });

    // Restore the correct selected preset or custom quantity.
    const initialValue = Number(questionCount.value);
    const isPreset = [10, 20, 50, 100].includes(initialValue);

    if (isPreset) {
        customCount.value = '';

        presetInputs.forEach(function (radio) {
            radio.checked = Number(radio.value) === initialValue;
        });
    } else if (initialValue >= 1 && initialValue <= 100) {
        customCount.value = initialValue;

        presetInputs.forEach(function (radio) {
            radio.checked = false;
        });
    }

    // Validate the selected quiz quantity before submitting.
    form.addEventListener('submit', function (event) {
        const amount = Number(questionCount.value);

        if (
            !Number.isInteger(amount) ||
            amount < 1 ||
            amount > 100
        ) {
            event.preventDefault();

            customCount.setCustomValidity(
                'Please select a preset or enter a number between 1 and 100.'
            );

            customCount.reportValidity();
            customCount.focus();

            return;
        }

        customCount.setCustomValidity('');

        if (generateButton) {
            generateButton.disabled = true;
            generateButton.textContent = 'Preparing your quiz...';
        }
    });
});
</script>
@endpush