
@extends('layouts.app')

@section('title', 'Your Cozy Study Corner')

@section('content')
<div class="shelly-upload-page">

    <div class="shelly-decoration butterfly butterfly-one">🦋</div>
    <div class="shelly-decoration butterfly butterfly-two">🦋</div>
    <div class="shelly-decoration little-bow bow-one">🎀</div>
    <div class="shelly-decoration little-bow bow-two">୨୧</div>

    <section class="welcome-grid">

        <div class="welcome-copy">

            <div class="welcome-label">
                <span>✿</span>
                A LITTLE SPACE FOR BIG IDEAS
            </div>

            <h1 class="page-title">
                Make studying<br>
                feel a little <span>sweeter.</span>
            </h1>

            <p class="page-description">
                Welcome to Shelly's cozy little study corner!
                Turn your notes and learning materials into
                summaries, flashcards, and fun quizzes.
                Let's make learning a little more magical.
            </p>

            <div class="welcome-details">
                <div>
                    <span class="detail-icon">♡</span>
                    Simple study tools
                </div>

                <div>
                    <span class="detail-icon">♡</span>
                    Made for your pace
                </div>

                <div>
                    <span class="detail-icon">♡</span>
                    Your own study space
                </div>
            </div>

            <div class="welcome-note">
                <span class="note-bunny">🐰</span>
                <div>
                    <strong>A little reminder</strong>
                    <p>You're doing lovely. One page at a time!</p>
                </div>
            </div>

            <div class="floating-bow">୨୧</div>
        </div>


        <section class="upload-card panel">

            <div class="card-top-decoration">
                <span>🎀</span>
            </div>

            <div class="upload-card-heading">
                <span class="page-kicker">LET'S GET STARTED</span>
                <h2>Add your study material</h2>

                <p class="upload-description">
                    Pick a file from your device and let Shelly
                    help you make studying easier.
                </p>
            </div>

            <div class="bunny-divider">
                <span>♡</span>
                <span>✿</span>
                <span>♡</span>
            </div>

            @if ($errors->any())
                <div class="error-message" role="alert">
                    <strong>Oopsie! Please check your file.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="success-message" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('sets.store', [], false) }}"
                enctype="multipart/form-data"
                class="upload-form"
            >
                @csrf

                <label class="file-drop" for="study-file">

                    <div class="upload-icon">
                        <span>♡</span>
                    </div>

                    <strong class="file-heading">
                        Choose your study file
                    </strong>

                    <span class="file-subheading">
                        PDF, DOCX, TXT, or an image
                    </span>

                    <span class="file-picker-button">
                        <span>＋</span> Browse files
                    </span>

                    <span class="selected-file" id="file-name">
                        No file selected yet
                    </span>

                    <input
                        id="study-file"
                        type="file"
                        name="file"
                        accept=".pdf,.docx,.txt,.jpg,.jpeg,.png,.webp"
                        required
                    >
                </label>

                <button class="button upload-button" type="submit">
                    <span>Upload my materials</span>
                    <span class="button-sparkle">✧</span>
                </button>
            </form>

            <div class="upload-footer">
                <span>🎀</span>
                Your study corner, one topic at a time ♡
                <span>🦋</span>
            </div>

        </section>

    </section>

    <div class="bottom-message">
        <span>୨୧</span>
        Made with a little love and lots of learning
        <span>୨୧</span>
    </div>

</div>


<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fredoka:wght@400;500;600;700&display=swap');

.shelly-upload-page {
    --shelly-pink: #e88db2;
    --shelly-dark-pink: #c76d97;
    --shelly-light-pink: #fff0f6;
    --shelly-pale: #fff9fb;
    --shelly-cream: #fffaf5;
    --shelly-text: #795568;
    --shelly-muted: #ad8d9d;

    position: relative;
    isolation: isolate;
    width: 100%;
    min-height: 680px;
    padding: 42px 0 18px;
    overflow: hidden;
    color: var(--shelly-text);
    font-family: 'DM Sans', sans-serif;
}

.shelly-upload-page *,
.shelly-upload-page *::before,
.shelly-upload-page *::after {
    box-sizing: border-box;
}

.welcome-grid {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(340px, 0.85fr);
    align-items: center;
    gap: clamp(35px, 6vw, 85px);
    max-width: 1120px;
    margin: 0 auto;
}

.welcome-copy {
    position: relative;
    padding: 30px 0 50px;
}

.welcome-label {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 21px;
    color: var(--shelly-dark-pink);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
}

.welcome-label span {
    font-size: 18px;
}

.page-title {
    margin: 0;
    color: #755267;
    font-family: 'Fredoka', sans-serif;
    font-size: clamp(38px, 4.4vw, 57px);
    font-weight: 600;
    line-height: 1.12;
    letter-spacing: -1.8px;
}

.page-title span {
    color: #df83a9;
}

.page-description {
    max-width: 510px;
    margin: 23px 0 0;
    color: #a08393;
    font-size: 15px;
    line-height: 1.9;
}

.welcome-details {
    display: flex;
    flex-wrap: wrap;
    gap: 13px 20px;
    margin-top: 28px;
}

.welcome-details div {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #987789;
    font-size: 12px;
    font-weight: 700;
}

.detail-icon {
    color: #df8caf;
    font-size: 17px;
}

.welcome-note {
    display: flex;
    align-items: center;
    gap: 13px;
    width: fit-content;
    max-width: 100%;
    margin-top: 42px;
    padding: 13px 20px;
    border: 1px solid #f6dce7;
    border-radius: 18px;
    background: rgba(255,255,255,.62);
}

.note-bunny {
    font-size: 27px;
}

.welcome-note strong {
    color: #bd7897;
    font-family: 'Fredoka', sans-serif;
    font-size: 14px;
}

.welcome-note p {
    margin: 3px 0 0;
    color: #a88d9b;
    font-size: 11px;
}

.floating-bow {
    position: absolute;
    right: 10%;
    bottom: -5px;
    color: #e9a4c0;
    font-size: 52px;
    transform: rotate(-12deg);
}


/* Upload card */

.upload-card {
    position: relative;
    padding: 36px;
    overflow: hidden;
    border: 1px solid #f3dce6;
    border-radius: 30px;
    background: rgba(255, 253, 252, .94);
    box-shadow: 0 18px 55px rgba(188, 124, 151, .10);
}

.card-top-decoration {
    position: absolute;
    top: 20px;
    right: 24px;
    display: grid;
    place-items: center;
    width: 43px;
    height: 43px;
    border-radius: 15px;
    background: #fff0f5;
    font-size: 23px;
    transform: rotate(8deg);
}

.upload-card-heading {
    padding-right: 24px;
}

.upload-card .page-kicker {
    color: #bf7797;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
}

.upload-card h2 {
    margin: 13px 0 9px;
    color: #755267;
    font-family: 'Fredoka', sans-serif;
    font-size: 25px;
    font-weight: 600;
    line-height: 1.3;
}

.upload-description {
    margin: 0;
    color: #a48998;
    font-size: 13px;
    line-height: 1.8;
}

.bunny-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin: 23px 0;
    color: #e6a1bd;
    font-size: 15px;
}

.bunny-divider::before,
.bunny-divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #f3dce6;
}


/* File upload */

.upload-form {
    display: grid;
    gap: 17px;
}

.file-drop {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 205px;
    padding: 24px 17px;
    border: 2px dashed #edbed1;
    border-radius: 23px;
    background:
        radial-gradient(circle at 10% 15%, #fff0f6 0, transparent 25%),
        #fff8fb;
    color: #89677a;
    text-align: center;
    cursor: pointer;
    transition: .2s ease;
}

.file-drop:hover {
    border-color: #df8eaf;
    background-color: #fff0f6;
    transform: translateY(-2px);
}

.file-drop:focus-within {
    outline: 3px solid rgba(224, 139, 175, .25);
    outline-offset: 3px;
}

.upload-icon {
    display: grid;
    place-items: center;
    width: 53px;
    height: 53px;
    border-radius: 19px;
    background: #ffe8f1;
    color: #d27fa2;
    font-size: 30px;
}

.file-heading {
    color: #805d72;
    font-family: 'Fredoka', sans-serif;
    font-size: 16px;
    font-weight: 500;
}

.file-subheading {
    color: #b194a3;
    font-size: 11px;
}

.file-picker-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-top: 3px;
    padding: 9px 17px;
    border: 1px solid #f0ccdb;
    border-radius: 30px;
    background: white;
    color: #bd7898;
    font-size: 11px;
    font-weight: 800;
}

.file-picker-button span {
    font-size: 17px;
}

.selected-file {
    max-width: 100%;
    overflow-wrap: anywhere;
    color: #b18d9f;
    font-size: 10px;
}

.file-drop input {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    opacity: 0;
    pointer-events: none;
}


/* Submit button */

.upload-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    width: 100%;
    min-height: 54px;
    padding: 13px 20px;
    border: none;
    border-radius: 16px;
    background: linear-gradient(110deg, #e99bb9, #d97fa6);
    box-shadow: 0 9px 20px rgba(216, 127, 166, .19);
    color: white;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.upload-button:hover {
    background: linear-gradient(110deg, #e58eb2, #cf719a);
    box-shadow: 0 12px 24px rgba(216, 127, 166, .25);
    transform: translateY(-2px);
}

.upload-button:active {
    transform: translateY(0);
}

.button-sparkle {
    font-size: 19px;
}

.upload-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    margin-top: 21px;
    color: #b28fa0;
    font-size: 10px;
}

.upload-footer span {
    font-size: 13px;
}


/* Messages */

.error-message,
.success-message {
    margin: 15px 0;
    padding: 13px 16px;
    border-radius: 14px;
    font-size: 12px;
    line-height: 1.6;
}

.error-message {
    border: 1px solid #f1c2ce;
    background: #fff1f3;
    color: #a34e69;
}

.error-message ul {
    margin: 6px 0 0;
    padding-left: 19px;
}

.success-message {
    border: 1px solid #d9e9d8;
    background: #f4fbf2;
    color: #668d65;
}


/* Decorations */

.shelly-decoration {
    position: absolute;
    z-index: 0;
    pointer-events: none;
    user-select: none;
}

.butterfly {
    color: #e8a2bd;
    font-size: 29px;
    opacity: .72;
}

.butterfly-one {
    top: 35px;
    left: 5%;
    transform: rotate(-17deg);
}

.butterfly-two {
    right: 4%;
    bottom: 100px;
    font-size: 24px;
    transform: rotate(15deg);
}

.little-bow {
    color: #e4a0ba;
    font-size: 38px;
    opacity: .7;
}

.bow-one {
    top: 45%;
    left: -5px;
    transform: rotate(-15deg);
}

.bow-two {
    right: 3%;
    top: 15%;
    transform: rotate(12deg);
}

.bottom-message {
    position: relative;
    z-index: 1;
    margin: 70px auto 0;
    color: #b391a1;
    text-align: center;
    font-size: 11px;
    letter-spacing: .2px;
}

.bottom-message span {
    padding: 0 7px;
    color: #df9fba;
}


/* Responsive layout */

@media (max-width: 850px) {
    .shelly-upload-page {
        padding: 25px 0 20px;
    }

    .welcome-grid {
        grid-template-columns: 1fr;
        gap: 25px;
        max-width: 550px;
    }

    .welcome-copy {
        padding: 12px 5px 15px;
    }

    .page-title {
        font-size: clamp(39px, 7vw, 52px);
    }

    .welcome-note {
        margin-top: 25px;
    }

    .floating-bow {
        right: 7%;
        bottom: 0;
    }

    .upload-card {
        padding: 30px;
    }

    .bottom-message {
        margin-top: 45px;
    }
}

@media (max-width: 480px) {
    .welcome-grid {
        padding: 0 5px;
    }

    .welcome-copy {
        padding: 10px 5px;
    }

    .page-title {
        font-size: 38px;
        letter-spacing: -1px;
    }

    .page-description {
        font-size: 13px;
    }

    .welcome-details {
        gap: 12px;
    }

    .welcome-details div {
        font-size: 11px;
    }

    .welcome-note {
        padding: 11px 14px;
    }

    .upload-card {
        padding: 25px 19px;
        border-radius: 24px;
    }

    .upload-card h2 {
        font-size: 22px;
    }

    .file-drop {
        min-height: 190px;
    }

    .floating-bow,
    .bow-one,
    .bow-two {
        display: none;
    }

    .butterfly-one {
        top: 0;
        left: auto;
        right: 10px;
    }

    .bottom-message {
        font-size: 9px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .shelly-upload-page *,
    .shelly-upload-page *::before,
    .shelly-upload-page *::after {
        transition: none !important;
    }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('study-file');
    const fileName = document.getElementById('file-name');

    if (!fileInput || !fileName) return;

    fileInput.addEventListener('change', function () {
        const file = this.files && this.files[0];

        fileName.textContent = file
            ? 'Selected: ' + file.name
            : 'No file selected yet';
    });
});
</script>
@endsection
