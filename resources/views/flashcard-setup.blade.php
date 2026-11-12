
@extends('layouts.app')

@section('title', 'Flashcard Setup | Shelly')

@section('content')

<div class="flashcard-setup-page">

    <div class="setup-heading">
        <div>
            <span class="page-kicker">LET'S PREPARE YOUR FLASHCARDS</span>
            <h1 class="page-title">Flashcard Setup</h1>
            <p class="page-description">
                Choose how many flashcards Shelly should create from your study materials.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="setup-error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- STUDY MATERIAL --}}

    <div class="material-card">
        <div class="material-icon">✿</div>

        <div class="material-info">
            <span class="material-label">YOUR STUDY MATERIAL</span>
            <h3>{{ $set->title }}</h3>
            <p>Shelly will use this study set to prepare your flashcards.</p>
        </div>
    </div>

    {{-- FLASHCARD SETUP FORM --}}

    <form
        action="{{ route('sets.flashcards.prepare', $set) }}"
        method="POST"
        id="flashcardSetupForm"
    >
        @csrf

        {{-- CARD QUANTITY --}}

        <section class="quantity-section">

            <div class="section-heading">
                <div>
                    <h2>How many cards do you need?</h2>
                    <p>Select a quantity or enter your own number.</p>
                </div>
            </div>

            <div class="quantity-options">

                @foreach ([10, 20, 30, 50] as $quantity)
                    <label class="quantity-option">
                        <input
                            type="radio"
                            name="count"
                            value="{{ $quantity }}"
                            {{ (string) old('count', 20) === (string) $quantity ? 'checked' : '' }}
                        >

                        <span class="quantity-option-content">
                            <strong>{{ $quantity }}</strong>
                            <small>Cards</small>
                        </span>
                    </label>
                @endforeach

            </div>

            <div class="custom-quantity">
                <label for="customCount">
                    Or enter a custom quantity
                </label>

                <input
                    type="number"
                    id="customCount"
                    min="1"
                    max="100"
                    step="1"
                    placeholder="Enter a number (1–100)"
                    value="{{ old('custom_count') }}"
                >

                <p>Choose between 1 and 100 flashcards.</p>
            </div>

            <div class="generation-note">
                <span class="note-icon">✧</span>

                <p>
                    Shelly will organize the important concepts from your materials
                    into question-and-answer flashcards.
                </p>
            </div>

        </section>

        {{-- CONTINUE BUTTON --}}

        <div class="setup-continue-section">

            <div class="continue-message">
                <span class="continue-decoration">୨୧</span>

                <div>
                    <strong>Ready to start studying?</strong>
                    <p>
                        Shelly is ready to prepare your personalized flashcards.
                    </p>
                </div>
            </div>

            <button type="submit" class="continue-button">
                <span>Continue with Shelly</span>
                <span class="continue-arrow">→</span>
            </button>

            <p class="continue-note">
                Your flashcards will be created using your selected study materials.
            </p>

        </div>

    </form>

</div>

<style>
    .flashcard-setup-page {
        max-width: 900px;
        margin: 0 auto;
        padding: 28px 20px 65px;
        color: #59434d;
    }

    .setup-heading {
        margin-bottom: 28px;
    }

    .page-kicker,
    .section-kicker {
        display: inline-block;
        color: #bd829b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
        margin-bottom: 9px;
    }

    .page-title {
        color: #654b57;
        font-family: "Playfair Display", Georgia, serif;
        font-size: clamp(30px, 5vw, 39px);
        font-weight: 500;
        margin: 0 0 9px;
    }

    .page-description {
        color: #987d89;
        font-size: 14px;
        line-height: 1.7;
        margin: 0;
    }

    .setup-error {
        background: #fff0f1;
        border: 1px solid #f0cbd2;
        border-radius: 14px;
        color: #a34e62;
        padding: 13px 17px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .setup-error p {
        margin: 3px 0;
    }

    /* STUDY MATERIAL CARD */

    .material-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #fffaf7;
        border: 1px solid #f0e1e3;
        border-radius: 19px;
        padding: 19px 21px;
        margin-bottom: 35px;
        box-shadow: 0 5px 18px rgba(128, 83, 101, 0.035);
    }

    .material-icon {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        background: #f9e6ed;
        color: #bd7c98;
        border-radius: 14px;
        font-size: 22px;
    }

    .material-label {
        color: #bd829b;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.5px;
    }

    .material-info h3 {
        color: #694e5b;
        font-size: 15px;
        margin: 4px 0;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .material-info p {
        color: #a48d97;
        font-size: 12px;
        margin: 0;
        line-height: 1.5;
    }

    /* QUANTITY SECTION */

    .quantity-section {
        background: #ffffff;
        border: 1px solid #f0e5e8;
        border-radius: 22px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 7px 24px rgba(128, 83, 101, 0.035);
    }

    .section-heading {
        margin-bottom: 22px;
    }

    .section-heading h2 {
        color: #684d59;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 23px;
        font-weight: 500;
        margin: 0 0 7px;
    }

    .section-heading p {
        color: #a18a95;
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
    }

    /* QUANTITY OPTIONS */

    .quantity-options {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .quantity-option {
        cursor: pointer;
    }

    .quantity-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .quantity-option-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-height: 83px;
        background: #fffaf9;
        border: 1px solid #f0e1e5;
        border-radius: 15px;
        transition: all 0.2s ease;
    }

    .quantity-option-content strong {
        color: #795b69;
        font-size: 22px;
        font-weight: 700;
    }

    .quantity-option-content small {
        color: #b0929f;
        font-size: 11px;
    }

    .quantity-option input:checked + .quantity-option-content {
        background: #fbe8ef;
        border-color: #d99bb4;
        box-shadow: 0 0 0 2px rgba(217, 155, 180, 0.12);
    }

    .quantity-option input:checked + .quantity-option-content strong {
        color: #a85f7e;
    }

    .quantity-option:focus-within .quantity-option-content {
        outline: 2px solid #d99bb4;
        outline-offset: 3px;
    }

    /* CUSTOM QUANTITY */

    .custom-quantity {
        margin-top: 22px;
    }

    .custom-quantity label {
        display: block;
        color: #765b68;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .custom-quantity input {
        width: 100%;
        box-sizing: border-box;
        min-height: 46px;
        border: 1px solid #ead9df;
        border-radius: 12px;
        background: #fffdfc;
        padding: 0 14px;
        color: #684d59;
        font-family: "Nunito", sans-serif;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .custom-quantity input:focus {
        border-color: #d69bb2;
        box-shadow: 0 0 0 3px rgba(214, 155, 178, 0.13);
    }

    .custom-quantity p {
        color: #b09aa3;
        font-size: 10px;
        margin: 7px 0 0;
    }

    /* GENERATION NOTE */

    .generation-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #fff8fa;
        border: 1px solid #f5e6eb;
        border-radius: 12px;
        padding: 12px 14px;
        margin-top: 21px;
    }

    .note-icon {
        color: #c1849f;
        font-size: 17px;
        line-height: 1;
    }

    .generation-note p {
        color: #9b818d;
        font-size: 11px;
        line-height: 1.6;
        margin: 0;
    }

    /* CONTINUE SECTION */

    .setup-continue-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 25px 22px;
        border: 1px solid #f0dce4;
        border-radius: 20px;
        background: linear-gradient(145deg, #fff9fb, #fff2f6);
        box-shadow: 0 7px 24px rgba(128, 83, 101, 0.045);
    }

    .continue-message {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 13px;
        margin-bottom: 21px;
        text-align: left;
    }

    .continue-decoration {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 13px;
        background: #f9e5ed;
        color: #b66f8b;
        font-size: 20px;
    }

    .continue-message strong {
        display: block;
        color: #704e5e;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .continue-message p {
        color: #a18a95;
        font-size: 11px;
        line-height: 1.6;
        margin: 0;
    }

    .continue-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 13px;
        width: 100%;
        max-width: 390px;
        min-height: 54px;
        padding: 14px 24px;
        border: 1px solid #d995af;
        border-radius: 14px;
        background: linear-gradient(135deg, #e7a1b9, #d88da8);
        color: #ffffff;
        font-family: "Nunito", sans-serif;
        font-size: 13px;
        font-weight: 900;
        cursor: pointer;
        box-shadow: 0 7px 17px rgba(166, 95, 123, 0.16);
        transition: all 0.22s ease;
    }

    .continue-button:hover {
        border-color: #a65f7b;
        background: linear-gradient(135deg, #d88da8, #c97f9b);
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(166, 95, 123, 0.23);
    }

    .continue-button:active {
        transform: translateY(0);
    }

    .continue-button:focus-visible {
        outline: 3px solid rgba(166, 95, 123, 0.25);
        outline-offset: 4px;
    }

    .continue-arrow {
        font-size: 18px;
        transition: transform 0.2s ease;
    }

    .continue-button:hover .continue-arrow {
        transform: translateX(4px);
    }

    .continue-note {
        color: #b09aa3;
        font-size: 10px;
        line-height: 1.6;
        text-align: center;
        margin: 13px 0 0;
    }

    /* RESPONSIVE */

    @media (max-width: 600px) {
        .flashcard-setup-page {
            padding: 20px 14px 45px;
        }

        .quantity-section {
            padding: 19px 15px;
        }

        .quantity-options {
            grid-template-columns: repeat(2, 1fr);
        }

        .setup-continue-section {
            padding: 22px 16px;
        }

        .continue-button {
            max-width: none;
        }
    }

    @media (max-width: 360px) {
        .continue-message strong {
            font-size: 15px;
        }

        .continue-message p {
            font-size: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customInput = document.getElementById('customCount');
        const form = document.getElementById('flashcardSetupForm');

        const presetRadios = document.querySelectorAll(
            'input[name="count"][type="radio"]'
        );

        if (!customInput || !form) {
            return;
        }

        // Selecting a preset clears the custom quantity.

        presetRadios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (radio.checked) {
                    customInput.value = '';
                    customInput.setCustomValidity('');
                }
            });
        });

        // Typing a custom quantity clears the preset selection.

        customInput.addEventListener('input', function () {
            presetRadios.forEach(function (radio) {
                radio.checked = false;
            });

            customInput.setCustomValidity('');
        });

        // Validate the selected quantity before submitting.

        form.addEventListener('submit', function (event) {
            const customValue = customInput.value.trim();

            let hiddenInput = form.querySelector(
                'input[name="count"][type="hidden"]'
            );

            if (customValue !== '') {
                const number = Number(customValue);

                if (
                    !Number.isInteger(number) ||
                    number < 1 ||
                    number > 100
                ) {
                    event.preventDefault();

                    customInput.setCustomValidity(
                        'Please enter a whole number between 1 and 100.'
                    );

                    customInput.reportValidity();
                    customInput.focus();

                    return;
                }

                customInput.setCustomValidity('');

                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'count';
                    form.appendChild(hiddenInput);
                }

                hiddenInput.value = number;

            } else {
                if (hiddenInput) {
                    hiddenInput.remove();
                }

                const selectedPreset = form.querySelector(
                    'input[name="count"]:checked'
                );

                if (!selectedPreset) {
                    event.preventDefault();

                    alert('Please select a flashcard quantity.');
                }
            }
        });
    });
</script>

@endsection
