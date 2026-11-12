
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

        <div class="heading-decoration">୨୧</div>
    </div>

    <div class="material-panel">
        <div class="material-icon">✧</div>

        <div class="material-details">
            <span class="material-label">CREATING FLASHCARDS FOR</span>
            <h2>{{ $set->title }}</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="setup-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('sets.flashcards.prepare', $set) }}"
        class="setup-form"
    >
        @csrf

        <section class="setup-section">
            <div class="section-heading">
                <span class="section-number">01</span>

                <div>
                    <h2>How many flashcards do you need?</h2>
                    <p>
                        Choose a quantity that suits your study session.
                    </p>
                </div>
            </div>

            <div class="quantity-grid">

                @foreach ([10, 20, 30, 50] as $quantity)
                    <label class="quantity-option">
                        <input
                            type="radio"
                            name="count"
                            value="{{ $quantity }}"
                            {{ (int) old('count', 20) === $quantity ? 'checked' : '' }}
                        >

                        <span class="quantity-content">
                            <strong>{{ $quantity }}</strong>
                            <small>cards</small>
                        </span>
                    </label>
                @endforeach

            </div>

            <div class="custom-quantity">
                <label for="custom-count">
                    Or enter a custom quantity
                </label>

                <div class="custom-input-wrap">
                    <input
                        type="number"
                        id="custom-count"
                        min="1"
                        max="100"
                        value="{{ in_array((int) old('count', 20), [10, 20, 30, 50]) ? '' : old('count') }}"
                        placeholder="Enter 1–100"
                    >

                    <span>flashcards</span>
                </div>

                <p class="input-hint">
                    Choose between 1 and 100 flashcards.
                </p>
            </div>
        </section>

        <div class="generation-note">
            <span class="note-icon">✦</span>

            <div>
                <strong>A little note from Shelly</strong>
                <p>
                    Shelly will use your uploaded study materials to create
                    relevant questions and answers. The actual number may be
                    lower if your materials do not contain enough distinct information.
                </p>
            </div>
        </div>

        <div class="setup-actions">
            <a
                href="{{ route('sets.flashcards.create', $set) }}"
                class="back-link"
            >
                ← Back to creation options
            </a>

            <button type="submit" class="generate-button">
                Continue <span>→</span>
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
.flashcard-setup-page {
    max-width: 850px;
    margin: 0 auto;
    padding-bottom: 35px;
}

.flashcard-setup-page .setup-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.flashcard-setup-page .page-title {
    margin: 7px 0;
}

.flashcard-setup-page .page-kicker {
    color: #b97891;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
}

.flashcard-setup-page .page-description {
    margin: 0;
    color: #a18c96;
    font-size: 13px;
    line-height: 1.7;
}

.flashcard-setup-page .heading-decoration {
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

.flashcard-setup-page .material-panel {
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

.flashcard-setup-page .material-icon {
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

.flashcard-setup-page .material-label {
    color: #b98a9d;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .13em;
}

.flashcard-setup-page .material-details h2 {
    margin: 4px 0 0;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 17px;
    overflow-wrap: anywhere;
}

.flashcard-setup-page .setup-error {
    padding: 13px 16px;
    margin-bottom: 18px;
    border: 1px solid #f0cbd7;
    border-radius: 12px;
    background: #fff2f5;
    color: #a75c73;
    font-size: 13px;
}

.flashcard-setup-page .setup-form {
    display: grid;
    gap: 18px;
}

.flashcard-setup-page .setup-section {
    padding: 26px;
    border: 1px solid #efdde4;
    border-radius: 17px;
    background: #fffdfd;
    box-shadow: 0 8px 25px rgba(151, 95, 115, .05);
}

.flashcard-setup-page .section-heading {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 24px;
}

.flashcard-setup-page .section-number {
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

.flashcard-setup-page .section-heading h2 {
    margin: 2px 0 5px;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 19px;
}

.flashcard-setup-page .section-heading p {
    margin: 0;
    color: #a18c96;
    font-size: 12px;
    line-height: 1.6;
}

.flashcard-setup-page .quantity-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}

.flashcard-setup-page .quantity-option {
    position: relative;
    cursor: pointer;
}

.flashcard-setup-page .quantity-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.flashcard-setup-page .quantity-content {
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

.flashcard-setup-page .quantity-content strong {
    color: #795568;
    font-family: Georgia, serif;
    font-size: 29px;
}

.flashcard-setup-page .quantity-content small {
    margin-top: 3px;
    color: #a18c96;
    font-size: 11px;
}

.flashcard-setup-page .quantity-option input:checked + .quantity-content {
    border-color: #d99bb0;
    background: #fff3f7;
    box-shadow: 0 0 0 2px #f7e3eb;
}

.flashcard-setup-page .quantity-option input:focus-visible + .quantity-content {
    outline: 3px solid #d99bb0;
    outline-offset: 3px;
}

.flashcard-setup-page .custom-quantity {
    margin-top: 26px;
}

.flashcard-setup-page .custom-quantity > label {
    display: block;
    margin-bottom: 10px;
    color: #795568;
    font-size: 12px;
    font-weight: 800;
}

.flashcard-setup-page .custom-input-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.flashcard-setup-page .custom-input-wrap input {
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

.flashcard-setup-page .custom-input-wrap input:focus {
    outline: none;
    border-color: #d99bb0;
    box-shadow: 0 0 0 3px #fff0f5;
}

.flashcard-setup-page .custom-input-wrap > span {
    color: #a18c96;
    font-size: 12px;
}

.flashcard-setup-page .input-hint {
    margin: 7px 0 0;
    color: #a18c96;
    font-size: 10px;
}

.flashcard-setup-page .generation-note {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 18px;
    border: 1px solid #f0dce4;
    border-radius: 14px;
    background: #fff5f8;
}

.flashcard-setup-page .note-icon {
    color: #c47e9b;
    font-size: 20px;
}

.flashcard-setup-page .generation-note strong {
    color: #795568;
    font-size: 12px;
}

.flashcard-setup-page .generation-note p {
    margin: 5px 0 0;
    color: #a18c96;
    font-size: 11px;
    line-height: 1.7;
}

.flashcard-setup-page .setup-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-top: 5px;
}

.flashcard-setup-page .back-link {
    color: #a16f86;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
}

.flashcard-setup-page .back-link:hover {
    text-decoration: underline;
}

.flashcard-setup-page .generate-button {
    padding: 13px 22px;
    border: 0;
    border-radius: 12px;
    background: #d98eaa;
    color: white;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.flashcard-setup-page .generate-button:hover {
    background: #bd7894;
    transform: translateY(-1px);
}

.flashcard-setup-page .generate-button span {
    margin-left: 6px;
}

.flashcard-setup-page .bottom-note {
    margin: 22px 0 8px;
    color: #a17e8e;
    font-family: Georgia, serif;
    font-size: 12px;
    font-style: italic;
    text-align: center;
}

@media (max-width: 650px) {
    .flashcard-setup-page .quantity-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .flashcard-setup-page .setup-section {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .flashcard-setup-page .setup-heading {
        align-items: flex-start;
    }

    .flashcard-setup-page .heading-decoration {
        width: 48px;
        height: 48px;
        font-size: 22px;
    }

    .flashcard-setup-page .setup-actions {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .flashcard-setup-page .generate-button {
        width: 100%;
    }

    .flashcard-setup-page .back-link {
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const customInput = document.getElementById('custom-count');
    const presetInputs = document.querySelectorAll(
        '.flashcard-setup-page input[name="count"]'
    );

    if (!customInput) {
        return;
    }

    customInput.addEventListener('input', function () {
        const value = this.value;

        presetInputs.forEach(function (radio) {
            radio.checked = false;
        });

        if (value !== '') {
            const number = Number(value);

            if (Number.isInteger(number) && number >= 1 && number <= 100) {
                const hiddenInput = document.createElement('input');

                hiddenInput.type = 'hidden';
                hiddenInput.name = 'count';
                hiddenInput.value = number;
                hiddenInput.id = 'custom-count-submission';

                const oldHidden = document.getElementById(
                    'custom-count-submission'
                );

                if (oldHidden) {
                    oldHidden.remove();
                }

                document.querySelector('.flashcard-setup-page .setup-form')
                    .appendChild(hiddenInput);
            }
        } else {
            const oldHidden = document.getElementById(
                'custom-count-submission'
            );

            if (oldHidden) {
                oldHidden.remove();
            }
        }
    });

    presetInputs.forEach(function (radio) {
        radio.addEventListener('change', function () {
            customInput.value = '';

            const oldHidden = document.getElementById(
                'custom-count-submission'
            );

            if (oldHidden) {
                oldHidden.remove();
            }
        });
    });
});
</script>
@endpush
