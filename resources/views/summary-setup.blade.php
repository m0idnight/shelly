
@extends('layouts.app')

@section('title', 'Summary Setup — ' . $set->title)

@section('content')

<div class="setup-page">

    <div class="setup-heading">
        <div>
            <span class="page-kicker">✧ Let's prepare your notes</span>
            <h1 class="page-title">Summary Setup</h1>
            <p class="page-description">
                Choose how Shelly should organize your study material.
            </p>
        </div>
        <div class="heading-decoration">୨୧</div>
    </div>

    <div class="material-panel panel">
        <div class="material-icon">✧</div>
        <div>
            <span class="material-label">CREATING NOTES FOR</span>
            <h2>{{ $set->title }}</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="setup-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('sets.summary.prepare', $set) }}"
          class="setup-form">

        @csrf

        <section class="setup-section panel">
            <div class="section-heading">
                <span class="section-number">01</span>
                <div>
                    <h2>How detailed should your notes be?</h2>
                    <p>Choose how much information Shelly should include.</p>
                </div>
            </div>

            <div class="option-grid">

                <label class="option-card">
                    <input type="radio" name="length" value="quick"
                        {{ old('length', 'balanced') === 'quick' ? 'checked' : '' }}>
                    <span class="option-content">
                        <span class="option-icon">✦</span>
                        <strong>Quick review</strong>
                        <small>Short notes with only the most important ideas.</small>
                        <span class="option-tag">Less detail</span>
                    </span>
                </label>

                <label class="option-card">
                    <input type="radio" name="length" value="balanced"
                        {{ old('length', 'balanced') === 'balanced' ? 'checked' : '' }}>
                    <span class="option-content">
                        <span class="option-icon">✧</span>
                        <strong>Balanced</strong>
                        <small>Clear explanations with a useful amount of detail.</small>
                        <span class="option-tag">Recommended</span>
                    </span>
                </label>

                <label class="option-card">
                    <input type="radio" name="length" value="detailed"
                        {{ old('length') === 'detailed' ? 'checked' : '' }}>
                    <span class="option-content">
                        <span class="option-icon">୨୧</span>
                        <strong>Detailed notes</strong>
                        <small>More comprehensive explanations and relevant details.</small>
                        <span class="option-tag">More detail</span>
                    </span>
                </label>

            </div>
        </section>

        <section class="setup-section panel">
            <div class="section-heading">
                <span class="section-number">02</span>
                <div>
                    <h2>Choose your note format</h2>
                    <p>Decide how the information should be presented.</p>
                </div>
            </div>

            <div class="format-list">

                <label class="format-option">
                    <input type="radio" name="format" value="organized"
                        {{ old('format', 'organized') === 'organized' ? 'checked' : '' }}>
                    <span>
                        <strong>Organized notes</strong>
                        <small>Headings, subheadings, explanations, and bullet points.</small>
                    </span>
                </label>

                <label class="format-option">
                    <input type="radio" name="format" value="key_points"
                        {{ old('format') === 'key_points' ? 'checked' : '' }}>
                    <span>
                        <strong>Key points</strong>
                        <small>Concise bullet points and important terms to remember.</small>
                    </span>
                </label>

                <label class="format-option">
                    <input type="radio" name="format" value="exam_revision"
                        {{ old('format') === 'exam_revision' ? 'checked' : '' }}>
                    <span>
                        <strong>Exam revision</strong>
                        <small>Important definitions, concepts, comparisons, and facts.</small>
                    </span>
                </label>
                    <label class="format-option">
    <input type="radio" name="format" value="table"
        {{ old('format') === 'table' ? 'checked' : '' }}>
    <span>
        <strong>Table Format</strong>
        <small>
            Organize important concepts, definitions, comparisons,
            and explanations into clear tables.
        </small>
    </span>
</label>
            </div>
        </section>

        <section class="setup-section panel">
            <div class="section-heading">
                <span class="section-number">03</span>
                <div>
                    <h2>Anything else Shelly should focus on?</h2>
                    <p>Optional instructions to personalize your notes.</p>
                </div>
            </div>

            <textarea
                name="focus"
                class="focus-input"
                rows="5"
                maxlength="2000"
                placeholder="Example: Focus on the important definitions, explain difficult concepts in simple words, and include examples where possible."
            >{{ old('focus') }}</textarea>

            <div class="input-hint">
                Optional · Up to 2,000 characters
            </div>
        </section>

        <div class="setup-actions">
            <a href="{{ route('sets.summary', $set) }}" class="back-link">
                ← Back to summary
            </a>

            <button type="submit" class="button generate-button">
                Create My Summary <span>✧</span>
            </button>
        </div>

    </form>

    <p class="bottom-note">✧ Your notes, your way. One step at a time.</p>

</div>

<style>
.setup-page {
    max-width: 850px;
    margin: 0 auto;
}

.setup-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.setup-heading .page-title {
    margin: 7px 0;
}

.heading-decoration {
    display: grid;
    place-items: center;
    width: 62px;
    height: 62px;
    border: 1px solid #efd4df;
    border-radius: 50%;
    background: #fff1f6;
    color: #cf91aa;
    font-size: 29px;
    flex-shrink: 0;
}

.material-panel {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 17px 20px;
    margin-bottom: 20px;
}

.material-icon {
    display: grid;
    place-items: center;
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: #fff0f5;
    color: #c47e9b;
    font-size: 22px;
}

.material-label {
    color: #b98a9d;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .13em;
}

.material-panel h2 {
    margin: 4px 0 0;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 17px;
    overflow-wrap: anywhere;
}

.setup-error {
    padding: 13px 16px;
    margin-bottom: 18px;
    border: 1px solid #f0cbd7;
    border-radius: 12px;
    background: #fff2f5;
    color: #a75c73;
    font-size: 13px;
}

.setup-form {
    display: grid;
    gap: 18px;
}

.setup-section {
    padding: 25px;
}

.section-heading {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 20px;
}

.section-number {
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

.section-heading h2 {
    margin: 2px 0 5px;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 19px;
}

.section-heading p {
    margin: 0;
    color: #a18c96;
    font-size: 12px;
    line-height: 1.6;
}

.option-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}

.option-card {
    position: relative;
    display: block;
    cursor: pointer;
}

.option-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.option-content {
    display: flex;
    flex-direction: column;
    min-height: 175px;
    padding: 16px;
    border: 1px solid #f0e2e7;
    border-radius: 16px;
    background: #fffdfd;
    transition: .2s ease;
}

.option-card input:checked + .option-content {
    border-color: #d99bb0;
    background: #fff5f8;
    box-shadow: 0 0 0 2px #f7e3eb;
}

.option-card input:focus-visible + .option-content,
.format-option input:focus-visible {
    outline: 3px solid #d99bb0;
    outline-offset: 3px;
}

.option-icon {
    margin-bottom: 12px;
    color: #c47e9b;
    font-size: 21px;
}

.option-content strong,
.format-option strong {
    color: #795568;
    font-size: 13px;
}

.option-content small,
.format-option small {
    display: block;
    margin-top: 7px;
    color: #a18c96;
    font-size: 11px;
    line-height: 1.6;
}

.option-tag {
    align-self: flex-start;
    margin-top: auto;
    padding: 5px 8px;
    border-radius: 20px;
    background: #fff0f5;
    color: #b16f8a;
    font-size: 9px;
    font-weight: 800;
}

.format-list {
    display: grid;
    gap: 10px;
}

.format-option {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px;
    border: 1px solid #f0e2e7;
    border-radius: 13px;
    background: #fffdfd;
    cursor: pointer;
}

.format-option:has(input:checked) {
    border-color: #d99bb0;
    background: #fff5f8;
}

.format-option input {
    margin-top: 3px;
    accent-color: #bd7894;
}

.focus-input {
    display: block;
    width: 100%;
    padding: 14px;
    border: 1px solid #ecdce3;
    border-radius: 13px;
    background: #fffdfd;
    color: #755d69;
    font: inherit;
    font-size: 13px;
    line-height: 1.7;
    resize: vertical;
}

.focus-input:focus {
    outline: none;
    border-color: #d99bb0;
    box-shadow: 0 0 0 3px #fff0f5;
}

.focus-input::placeholder {
    color: #b9a6af;
}

.input-hint {
    margin-top: 7px;
    color: #a18c96;
    font-size: 10px;
    text-align: right;
}

.setup-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-top: 5px;
}

.back-link {
    color: #a16f86;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

.generate-button {
    padding: 13px 21px;
    border: 0;
    border-radius: 12px;
    background: #d98eaa;
    color: white;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.generate-button:hover {
    background: #bd7894;
    transform: translateY(-1px);
}

.generate-button span {
    margin-left: 5px;
}

.bottom-note {
    margin: 22px 0 8px;
    color: #a17e8e;
    font-family: Georgia, serif;
    font-size: 12px;
    font-style: italic;
    text-align: center;
}

@media (max-width: 650px) {
    .option-grid {
        grid-template-columns: 1fr;
    }

    .option-content {
        min-height: auto;
    }

    .option-tag {
        margin-top: 12px;
    }
}

@media (max-width: 480px) {
    .setup-section {
        padding: 19px;
    }

    .setup-actions {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .generate-button {
        width: 100%;
    }

    .back-link {
        text-align: center;
    }
}
</style>

@endsection
