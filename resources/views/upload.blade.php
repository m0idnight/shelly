
@extends('layouts.app')

@section('title', 'Upload Materials')

@section('content')

<div class="shelly-upload-page">

    {{-- Decorative details --}}
    <div class="page-decoration decoration-one">୨୧</div>
    <div class="page-decoration decoration-two">✿</div>
    <div class="page-decoration decoration-three">✧</div>

    <main class="upload-container">

        {{-- Welcome header --}}
        <header class="upload-welcome">
            <div class="welcome-topline">
                <span class="welcome-tag">
                    <span>✿</span>
                    YOUR LITTLE STUDY LIBRARY
                </span>

                <span class="welcome-bow">🎀</span>
            </div>

            <h1>
                Let's prepare your
                <span>study materials.</span>
            </h1>

            <p>
                Bring your notes, documents, and learning resources
                together in one cozy little space.
            </p>

            <div class="welcome-decoration">
                <span></span>
                <span>✿</span>
                <span></span>
            </div>
        </header>


        {{-- Main upload card --}}
        <section class="upload-card">

            <div class="card-heading">
                <div class="heading-icon">
                    <span>📚</span>
                </div>

                <div>
                    <span class="eyebrow">A FRESH STUDY SESSION</span>
                    <h2>Create a new study set</h2>
                    <p>First, give your materials a name.</p>
                </div>

                <div class="heading-ribbon">୨୧</div>
            </div>


            {{-- Error messages --}}
            @if ($errors->any())
                <div class="form-message error-message" role="alert">
                    <strong>Let's fix a few things!</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success message --}}
            @if (session('success'))
                <div class="form-message success-message" role="status">
                    {{ session('success') }}
                </div>
            @endif


            <form
                method="POST"
                action="{{ route('sets.store', [], false) }}"
                enctype="multipart/form-data"
                id="upload-form"
            >
                @csrf

                {{-- Study set name --}}
                <div class="form-section">

                    <label for="study-title" class="field-label">
                        <span class="field-number">01</span>
                        Name your study set
                    </label>

                    <p class="field-description">
                        Choose a name that makes your materials easy to find.
                    </p>

                    <input
                        type="text"
                        id="study-title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="e.g. Biology — Chapter 1"
                        maxlength="255"
                        required
                        class="title-input"
                    >

                    @error('title')
                        <span class="field-error">{{ $message }}</span>
                    @enderror

                </div>


                {{-- File upload --}}
                <div class="form-section file-section">

                    <div class="file-section-heading">
                        <div>
                            <label class="field-label">
                                <span class="field-number">02</span>
                                Add your materials
                            </label>

                            <p class="field-description">
                                Select the files you want to study.
                            </p>
                        </div>

                        <span class="file-count-badge" id="file-count">
                            0 files selected
                        </span>
                    </div>


                    {{-- Hidden actual file input --}}
                    <input
                        type="file"
                        id="study-file"
                        name="files[]"
                        accept=".pdf,.txt,.docx,.pptx,.jpg,.jpeg,.png,.webp"
                        multiple
                        hidden
                    >


                    {{-- Drop area --}}
                    <label
                        for="study-file"
                        class="file-drop"
                        id="file-drop"
                        tabindex="0"
                    >

                        <div class="drop-illustration">
                            <div class="folder-icon">
                                <svg
                                    viewBox="0 0 64 64"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M7 18C7 14.7 9.7 12 13 12H26L32 19H51C54.3 19 57 21.7 57 25V48C57 51.3 54.3 54 51 54H13C9.7 54 7 51.3 7 48V18Z"
                                        fill="#F5C8D7"
                                    />
                                    <path
                                        d="M7 27C7 23.7 9.7 21 13 21H51C54.3 21 57 23.7 57 27V48C57 51.3 54.3 54 51 54H13C9.7 54 7 51.3 7 48V27Z"
                                        fill="#EAAFC2"
                                    />
                                    <path
                                        d="M32 29V44M25 36.5L32 29L39 36.5"
                                        stroke="#9E5D78"
                                        stroke-width="2.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </div>

                            <span class="drop-sparkle sparkle-left">✧</span>
                            <span class="drop-sparkle sparkle-right">✿</span>
                        </div>

                        <h3>Drop your files here</h3>

                        <p class="drop-subtitle">
                            or browse files from your device
                        </p>

                        <span class="browse-button">
                            <span>＋</span>
                            Browse files
                        </span>

                        <span class="supported-formats">
                            PDF · DOCX · PPTX · TXT · JPG · PNG · WEBP
                        </span>

                        <span class="drop-helper">
                            You can select multiple files at once.
                        </span>

                    </label>


                    {{-- Selected files --}}
                    <div
                        class="file-selection"
                        id="file-selection"
                        hidden
                    >

                        <div class="selection-header">
                            <div>
                                <span class="selection-title">
                                    YOUR SELECTED MATERIALS
                                </span>

                                <p>Review your files before uploading.</p>
                            </div>

                            <span
                                class="selection-limit"
                                id="selection-limit"
                            >
                                0 / 10
                            </span>
                        </div>

                        <div
                            class="selected-file-list"
                            id="selected-file-list"
                        ></div>

                        <button
                            type="button"
                            class="add-files-button"
                            id="add-files-button"
                        >
                            <span>＋</span>
                            Add more files
                        </button>

                    </div>


                    {{-- Validation notice --}}
                    <p
                        class="file-limit-message"
                        id="file-limit-message"
                        role="alert"
                        aria-live="polite"
                        hidden
                    ></p>


                    {{-- Upload information --}}
                    <div class="upload-info">
                        <span class="info-icon">ⓘ</span>

                        <p>
                            Up to <strong>10 files</strong>,
                            <strong>25 MB per file</strong>,
                            and <strong>128 MB combined</strong>.
                        </p>
                    </div>

                </div>


                {{-- Create button --}}
                <div class="submit-area">

                    <button
                        type="submit"
                        class="create-button"
                        id="upload-button"
                    >
                        <span>Create My Study Set</span>
                        <span class="button-sparkle">✧</span>
                    </button>

                    <p class="submit-note">
                        Your materials will be organized into a new study set.
                    </p>

                </div>

            </form>

        </section>


        {{-- Small footer note --}}
        <footer class="upload-footer">
            <span>🎀</span>
            <p>One little step at a time. You've got this!</p>
            <span>✿</span>
        </footer>

    </main>

</div>


{{-- File preview modal --}}
<div class="preview-modal" id="preview-modal" hidden>

    <div
        class="preview-backdrop"
        id="preview-backdrop"
    ></div>

    <section
        class="preview-window"
        role="dialog"
        aria-modal="true"
        aria-labelledby="preview-title"
    >

        <header class="preview-header">

            <div>
                <span class="eyebrow">YOUR STUDY MATERIAL</span>
                <h3 id="preview-title">File Preview</h3>
            </div>

            <button
                type="button"
                class="preview-close"
                id="preview-close"
                aria-label="Close preview"
            >
                ×
            </button>

        </header>

        <div
            class="preview-content"
            id="preview-content"
        ></div>

        <footer class="preview-footer">
            <span>🎀</span>
            Your file is ready for your study set.
        </footer>

    </section>

</div>


<style>

/* =========================================
   BASE
========================================= */

.shelly-upload-page {
    --su-pink: #d98eaa;
    --su-pink-dark: #a96380;
    --su-pink-light: #fff0f5;
    --su-pink-soft: #f9e6ec;
    --su-cream: #fffaf6;
    --su-ink: #765666;
    --su-muted: #a88d9b;
    --su-border: #f0dce4;

    position: relative;
    isolation: isolate;
    width: 100%;
    min-height: 75vh;
    padding: 45px 20px 35px;
    box-sizing: border-box;
    overflow: hidden;
    color: var(--su-ink);
    font-family: "Nunito", sans-serif;
}

.shelly-upload-page *,
.shelly-upload-page *::before,
.shelly-upload-page *::after {
    box-sizing: border-box;
}

.upload-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 760px;
    margin: 0 auto;
}


/* =========================================
   DECORATIONS
========================================= */

.page-decoration {
    position: absolute;
    z-index: -1;
    color: #e7b4c5;
    opacity: .55;
    pointer-events: none;
    user-select: none;
}

.decoration-one {
    top: 100px;
    left: 5%;
    font-size: 55px;
    transform: rotate(-12deg);
}

.decoration-two {
    top: 420px;
    right: 6%;
    font-size: 35px;
}

.decoration-three {
    top: 650px;
    left: 9%;
    font-size: 28px;
}


/* =========================================
   WELCOME HEADER
========================================= */

.upload-welcome {
    position: relative;
    margin-bottom: 28px;
    padding: 30px 35px 27px;
    overflow: hidden;
    border: 1px solid #efd6df;
    border-radius: 24px;
    background-color: #f9e9ed;
    background-image:
        linear-gradient(rgba(255,255,255,.30) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.30) 1px, transparent 1px);
    background-size: 18px 18px;
}

.welcome-topline {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 22px;
}

.welcome-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #a16f88;
    font-size: 10px;
    font-weight: 900;
    letter-spacing: 1.8px;
}

.welcome-tag span {
    font-size: 17px;
}

.welcome-bow {
    font-size: 25px;
}

.upload-welcome h1 {
    max-width: 580px;
    margin: 0;
    color: #704d60;
    font-family: "Georgia", serif;
    font-size: clamp(30px, 5vw, 43px);
    font-weight: 500;
    line-height: 1.22;
    letter-spacing: -.8px;
}

.upload-welcome h1 span {
    display: block;
    color: #c77e9b;
    font-style: italic;
}

.upload-welcome > p {
    max-width: 500px;
    margin: 15px 0 0;
    color: #947987;
    font-size: 13px;
    line-height: 1.8;
}

.welcome-decoration {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100px;
    margin-top: 23px;
    color: #d18ca7;
}

.welcome-decoration span:first-child,
.welcome-decoration span:last-child {
    flex: 1;
    height: 1px;
    background: #dfb5c5;
}


/* =========================================
   MAIN CARD
========================================= */

.upload-card {
    position: relative;
    padding: 32px;
    border: 1px solid var(--su-border);
    border-radius: 25px;
    background: rgba(255, 253, 251, .98);
    box-shadow: 0 12px 35px rgba(143, 95, 116, .07);
}

.card-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-bottom: 25px;
    border-bottom: 1px solid #f1e3e7;
}

.heading-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 51px;
    height: 51px;
    border: 1px solid #f0d6df;
    border-radius: 16px;
    background: #fff0f5;
    font-size: 23px;
}

.card-heading > div:nth-child(2) {
    flex: 1;
    min-width: 0;
}

.eyebrow {
    color: #b17b93;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 1.8px;
}

.card-heading h2 {
    margin: 5px 0 3px;
    color: #765366;
    font-family: Georgia, serif;
    font-size: 23px;
    font-weight: 500;
}

.card-heading p {
    margin: 0;
    color: var(--su-muted);
    font-size: 11px;
}

.heading-ribbon {
    color: #d99bb2;
    font-size: 31px;
}


/* =========================================
   FORM SECTIONS
========================================= */

.form-section {
    margin-top: 27px;
}

.field-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #79596d;
    font-size: 13px;
    font-weight: 900;
}

.field-number {
    display: grid;
    place-items: center;
    width: 25px;
    height: 25px;
    border-radius: 9px;
    background: #f9e8ee;
    color: #b77b94;
    font-size: 10px;
    font-weight: 900;
}

.field-description {
    margin: 8px 0 12px 35px;
    color: #a88d9b;
    font-size: 11px;
    line-height: 1.6;
}

.title-input {
    display: block;
    width: 100%;
    min-height: 49px;
    padding: 12px 15px;
    border: 1px solid #ebd2dc;
    border-radius: 12px;
    outline: none;
    background: #fffafd;
    color: #765666;
    font-family: "Nunito", sans-serif;
    font-size: 13px;
    transition: border-color .2s, box-shadow .2s;
}

.title-input::placeholder {
    color: #c4a9b5;
}

.title-input:focus {
    border-color: #d98eaa;
    box-shadow: 0 0 0 3px rgba(217, 142, 170, .14);
}

.field-error {
    display: block;
    margin-top: 7px;
    color: #b04f69;
    font-size: 11px;
}


/* =========================================
   FILE SECTION
========================================= */

.file-section {
    padding-top: 24px;
    border-top: 1px solid #f1e3e7;
}

.file-section-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 17px;
}

.file-section-heading .field-description {
    margin-bottom: 0;
}

.file-count-badge {
    flex-shrink: 0;
    padding: 7px 10px;
    border: 1px solid #efd6df;
    border-radius: 30px;
    background: #fff5f8;
    color: #aa748c;
    font-size: 10px;
    font-weight: 900;
}


/* =========================================
   DROP ZONE
========================================= */

.file-drop {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 265px;
    padding: 28px 18px;
    border: 2px dashed #e8bdcc;
    border-radius: 19px;
    outline: none;
    background:
        radial-gradient(circle at 15% 15%, #fff0f5 0, transparent 30%),
        radial-gradient(circle at 85% 85%, #fff1f5 0, transparent 30%),
        #fffafd;
    text-align: center;
    cursor: pointer;
    transition: background .2s, border-color .2s, transform .2s;
}

.file-drop:hover,
.file-drop.drag-active {
    border-color: #d58ca8;
    background-color: #fff2f6;
}

.file-drop:focus-visible {
    outline: 3px solid #efc6d5;
    outline-offset: 4px;
}

.drop-illustration {
    position: relative;
    margin-bottom: 14px;
}

.folder-icon {
    display: grid;
    place-items: center;
    width: 75px;
    height: 75px;
    border-radius: 25px;
    background: #fbe7ee;
}

.folder-icon svg {
    width: 60px;
    height: 60px;
}

.drop-sparkle {
    position: absolute;
    color: #d58ca8;
}

.sparkle-left {
    top: 0;
    left: -16px;
    font-size: 19px;
}

.sparkle-right {
    right: -17px;
    bottom: 4px;
    font-size: 17px;
}

.file-drop h3 {
    margin: 0;
    color: #79576b;
    font-family: Georgia, serif;
    font-size: 20px;
    font-weight: 500;
}

.drop-subtitle {
    margin: 7px 0 15px;
    color: #a88d9b;
    font-size: 11px;
}

.browse-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 39px;
    padding: 8px 19px;
    border: 1px solid #e7b9ca;
    border-radius: 30px;
    background: #f9e8ee;
    color: #9f6680;
    font-size: 11px;
    font-weight: 900;
    transition: background .2s, transform .2s;
}

.file-drop:hover .browse-button {
    background: #f5dce6;
    transform: translateY(-1px);
}

.browse-button span {
    font-size: 16px;
}

.supported-formats {
    margin-top: 17px;
    color: #a98295;
    font-size: 10px;
    font-weight: 900;
    letter-spacing: .4px;
}

.drop-helper {
    margin-top: 6px;
    color: #b59eaa;
    font-size: 10px;
}


/* =========================================
   SELECTED FILES
========================================= */

.file-selection {
    margin-top: 17px;
    padding: 16px;
    border: 1px solid #efdae2;
    border-radius: 16px;
    background: #fffafd;
}

.file-selection[hidden] {
    display: none;
}

.selection-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 13px;
}

.selection-title {
    color: #a16f88;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 1.5px;
}

.selection-header p {
    margin: 5px 0 0;
    color: #b096a2;
    font-size: 10px;
}

.selection-limit {
    color: #b17b93;
    font-size: 10px;
    font-weight: 900;
}

.selected-file-list {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.file-chip {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
    padding: 10px;
    border: 1px solid #f0e0e6;
    border-radius: 13px;
    background: #fff;
}

.file-chip-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 39px;
    height: 39px;
    border-radius: 12px;
    background: #fbeaf0;
    color: #b2718c;
    font-size: 10px;
    font-weight: 900;
}

.file-chip-details {
    display: flex;
    flex: 1;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.file-chip-label {
    overflow: hidden;
    color: #805e72;
    font-size: 11px;
    font-weight: 900;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.file-chip-size {
    color: #b097a3;
    font-size: 10px;
}

.file-chip-actions {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-shrink: 0;
}

.file-chip-preview {
    min-height: 32px;
    padding: 6px 10px;
    border: 1px solid #ebd0db;
    border-radius: 25px;
    background: #fff7fa;
    color: #a36e86;
    font-family: inherit;
    font-size: 10px;
    font-weight: 900;
    cursor: pointer;
}

.file-chip-preview:hover {
    background: #f9e7ee;
}

.file-chip-remove {
    display: grid;
    place-items: center;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid #f0dce4;
    border-radius: 50%;
    background: #fff3f7;
    color: #b87591;
    font-size: 19px;
    cursor: pointer;
}

.file-chip-remove:hover {
    background: #f8e0e9;
}

.add-files-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-top: 13px;
    padding: 8px 13px;
    border: 1px solid #ebd0db;
    border-radius: 25px;
    background: white;
    color: #a36e86;
    font-family: inherit;
    font-size: 10px;
    font-weight: 900;
    cursor: pointer;
}

.add-files-button:hover {
    background: #fff2f6;
}

.add-files-button:disabled {
    opacity: .5;
    cursor: not-allowed;
}


/* =========================================
   UPLOAD LIMITS AND INFO
========================================= */

.file-limit-message {
    margin: 12px 0 0;
    padding: 11px 13px;
    border: 1px solid #efc4cf;
    border-radius: 11px;
    background: #fff0f3;
    color: #a34e65;
    font-size: 11px;
    line-height: 1.6;
}

.file-limit-message[hidden] {
    display: none;
}

.upload-info {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 14px;
    padding: 12px 13px;
    border: 1px solid #f0e3e8;
    border-radius: 11px;
    background: #fffafc;
}

.info-icon {
    color: #c28ba2;
    font-size: 14px;
}

.upload-info p {
    margin: 0;
    color: #a58b98;
    font-size: 10px;
    line-height: 1.7;
}

.upload-info strong {
    color: #926a7e;
}


/* =========================================
   SUBMIT AREA
========================================= */

.submit-area {
    margin-top: 29px;
    padding-top: 22px;
    border-top: 1px solid #f1e3e7;
    text-align: center;
}

.create-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 11px;
    width: 100%;
    min-height: 54px;
    padding: 13px 20px;
    border: 0;
    border-radius: 14px;
    background: linear-gradient(110deg, #e5a0b7, #d587a5);
    box-shadow: 0 7px 16px rgba(205, 125, 157, .17);
    color: #fff;
    font-family: "Nunito", sans-serif;
    font-size: 13px;
    font-weight: 900;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s, opacity .2s;
}

.create-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 21px rgba(205, 125, 157, .23);
}

.create-button:active {
    transform: translateY(0);
}

.create-button:disabled {
    opacity: .65;
    cursor: wait;
    transform: none;
}

.button-sparkle {
    font-size: 19px;
}

.submit-note {
    margin: 11px 0 0;
    color: #b096a2;
    font-size: 10px;
}


/* =========================================
   FOOTER
========================================= */

.upload-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 24px;
    color: #aa8798;
}

.upload-footer span {
    color: #d69bb1;
    font-size: 15px;
}

.upload-footer p {
    margin: 0;
    font-size: 10px;
}


/* =========================================
   FEEDBACK MESSAGES
========================================= */

.form-message {
    margin: 18px 0;
    padding: 13px 15px;
    border-radius: 12px;
    font-size: 11px;
    line-height: 1.7;
}

.error-message {
    border: 1px solid #efc4cf;
    background: #fff0f3;
    color: #a34e65;
}

.error-message ul {
    margin: 7px 0 0;
    padding-left: 19px;
}

.success-message {
    border: 1px solid #d8e9d8;
    background: #f4fbf2;
    color: #668d65;
}


/* =========================================
   PREVIEW MODAL
========================================= */

.preview-modal {
    position: fixed;
    z-index: 9999;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 20px;
}

.preview-modal[hidden] {
    display: none;
}

.preview-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(69, 43, 58, .55);
    backdrop-filter: blur(4px);
}

.preview-window {
    position: relative;
    display: flex;
    flex-direction: column;
    width: min(850px, 100%);
    max-height: 90vh;
    overflow: hidden;
    border: 1px solid #efdce3;
    border-radius: 19px;
    background: #fffdfb;
    box-shadow: 0 20px 60px rgba(45, 25, 35, .2);
}

.preview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 20px;
    border-bottom: 1px solid #f0e1e6;
}

.preview-header h3 {
    margin: 5px 0 0;
    color: #765366;
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 500;
    overflow-wrap: anywhere;
}

.preview-close {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 37px;
    height: 37px;
    border: 1px solid #efdce3;
    border-radius: 50%;
    background: #fff3f7;
    color: #a96e87;
    font-size: 23px;
    cursor: pointer;
}

.preview-content {
    display: grid;
    place-items: center;
    min-height: 250px;
    padding: 18px;
    overflow: auto;
    background: #fff9fb;
}

.preview-image {
    display: block;
    max-width: 100%;
    max-height: 65vh;
    object-fit: contain;
    border-radius: 9px;
}

.preview-pdf {
    width: 100%;
    height: 65vh;
    border: 0;
    border-radius: 9px;
    background: #fff;
}

.preview-text {
    width: 100%;
    max-height: 65vh;
    margin: 0;
    padding: 17px;
    overflow: auto;
    border: 1px solid #f0dce4;
    border-radius: 11px;
    background: white;
    color: #765666;
    font-family: monospace;
    font-size: 12px;
    line-height: 1.7;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.preview-notice {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    max-width: 430px;
    padding: 25px;
    text-align: center;
}

.preview-notice-icon {
    display: grid;
    place-items: center;
    width: 62px;
    height: 62px;
    border-radius: 20px;
    background: #fbe8ef;
    color: #b87591;
    font-size: 25px;
}

.preview-notice strong {
    color: #79576b;
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 500;
}

.preview-notice p {
    margin: 0;
    color: #a88d9b;
    font-size: 12px;
    line-height: 1.8;
}

.preview-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 13px;
    border-top: 1px solid #f0e1e6;
    color: #a88d9b;
    font-size: 10px;
}


/* =========================================
   RESPONSIVE — TABLET
========================================= */

@media (max-width: 768px) {

    .shelly-upload-page {
        padding: 30px 17px;
    }

    .upload-container {
        max-width: 650px;
    }

    .upload-welcome {
        padding: 27px;
    }

    .upload-card {
        padding: 26px;
    }

    .decoration-one {
        left: -10px;
    }

    .decoration-two {
        right: -5px;
    }

}


/* =========================================
   RESPONSIVE — MOBILE
========================================= */

@media (max-width: 480px) {

    .shelly-upload-page {
        padding: 18px 12px 25px;
    }

    .upload-welcome {
        padding: 22px 19px;
        border-radius: 19px;
    }

    .welcome-topline {
        margin-bottom: 17px;
    }

    .welcome-tag {
        font-size: 8px;
        letter-spacing: 1.2px;
    }

    .upload-welcome h1 {
        font-size: 31px;
    }

    .upload-welcome > p {
        font-size: 11px;
    }

    .upload-card {
        padding: 20px 16px;
        border-radius: 19px;
    }

    .card-heading {
        gap: 11px;
    }

    .heading-icon {
        width: 43px;
        height: 43px;
        font-size: 19px;
    }

    .card-heading h2 {
        font-size: 19px;
    }

    .heading-ribbon {
        font-size: 23px;
    }

    .file-section-heading {
        flex-direction: column;
        gap: 10px;
    }

    .file-drop {
        min-height: 245px;
        padding: 22px 12px;
    }

    .file-drop h3 {
        font-size: 18px;
    }

    .supported-formats {
        font-size: 9px;
    }

    .file-chip {
        gap: 7px;
        padding: 8px;
    }

    .file-chip-icon {
        width: 34px;
        height: 34px;
    }

    .file-chip-preview {
        padding: 6px 8px;
        font-size: 9px;
    }

    .file-chip-remove {
        width: 29px;
        height: 29px;
    }

    .preview-modal {
        padding: 10px;
    }

    .preview-window {
        max-height: 95vh;
    }

    .preview-pdf {
        height: 65vh;
    }

    .decoration-one,
    .decoration-two,
    .decoration-three {
        display: none;
    }

}


/* =========================================
   REDUCED MOTION
========================================= */

@media (prefers-reduced-motion: reduce) {

    .shelly-upload-page *,
    .shelly-upload-page *::before,
    .shelly-upload-page *::after {
        scroll-behavior: auto !important;
        transition: none !important;
        animation: none !important;
    }

}

</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('study-file');
    const fileCount = document.getElementById('file-count');
    const fileSelection = document.getElementById('file-selection');
    const fileList = document.getElementById('selected-file-list');
    const selectionLimit = document.getElementById('selection-limit');
    const addFilesButton = document.getElementById('add-files-button');
    const limitMessage = document.getElementById('file-limit-message');
    const uploadForm = document.getElementById('upload-form');
    const fileDrop = document.getElementById('file-drop');
    const uploadButton = document.getElementById('upload-button');

    const previewModal = document.getElementById('preview-modal');
    const previewContent = document.getElementById('preview-content');
    const previewTitle = document.getElementById('preview-title');
    const previewClose = document.getElementById('preview-close');
    const previewBackdrop = document.getElementById('preview-backdrop');

    if (
        !fileInput ||
        !fileCount ||
        !fileSelection ||
        !fileList ||
        !selectionLimit ||
        !addFilesButton ||
        !limitMessage ||
        !uploadForm ||
        !fileDrop
    ) {
        return;
    }


    // =====================================
    // UPLOAD LIMITS
    // =====================================

    const MAX_FILES = 10;

    const MAX_FILE_SIZE = 25 * 1024 * 1024;

    const MAX_TOTAL_SIZE = 128 * 1024 * 1024;

    const ALLOWED_EXTENSIONS = [
        'pdf',
        'txt',
        'docx',
        'pptx',
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    let selectedFiles = [];
    let currentPreviewUrl = null;
    let currentPreviewFile = null;
    let previewTrigger = null;
    let previewRequestId = 0;
    let previousBodyOverflow = '';


    // =====================================
    // FILE HELPERS
    // =====================================

    function formatFileSize(bytes) {

        if (bytes < 1024) {
            return bytes + ' B';
        }

        if (bytes < 1024 * 1024) {
            return (bytes / 1024).toFixed(1) + ' KB';
        }

        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';

    }


    function getTotalSize() {

        return selectedFiles.reduce(function (total, file) {
            return total + file.size;
        }, 0);

    }


    function getExtension(file) {

        return file.name.split('.').pop().toLowerCase();

    }


    function showLimitMessage(message) {

        limitMessage.textContent = message;
        limitMessage.hidden = false;

    }


    function clearLimitMessage() {

        limitMessage.textContent = '';
        limitMessage.hidden = true;

    }


    // =====================================
    // INPUT SYNCHRONIZATION
    // =====================================

    function updateInputFiles() {

        const transfer = new DataTransfer();

        selectedFiles.forEach(function (file) {
            transfer.items.add(file);
        });

        fileInput.files = transfer.files;

    }


    // =====================================
    // PREVIEW MANAGEMENT
    // =====================================

    function releasePreviewUrl() {

        if (currentPreviewUrl) {
            URL.revokeObjectURL(currentPreviewUrl);
            currentPreviewUrl = null;
        }

    }


    function closePreview() {

        previewRequestId++;

        previewModal.hidden = true;
        previewContent.replaceChildren();

        releasePreviewUrl();

        currentPreviewFile = null;

        document.body.style.overflow = previousBodyOverflow;

        if (previewTrigger && document.contains(previewTrigger)) {
            previewTrigger.focus();
        }

        previewTrigger = null;

    }


    function showNotice(title, message, icon) {

        previewContent.replaceChildren();

        const notice = document.createElement('div');
        notice.className = 'preview-notice';

        const iconBox = document.createElement('div');
        iconBox.className = 'preview-notice-icon';
        iconBox.textContent = icon;

        const heading = document.createElement('strong');
        heading.textContent = title;

        const description = document.createElement('p');
        description.textContent = message;

        notice.appendChild(iconBox);
        notice.appendChild(heading);
        notice.appendChild(description);

        previewContent.appendChild(notice);

    }


    async function previewFile(file, trigger) {

        previewRequestId++;

        const requestId = previewRequestId;

        releasePreviewUrl();
        previewContent.replaceChildren();

        currentPreviewFile = file;
        previewTrigger = trigger;

        previewTitle.textContent = file.name + ' Preview';

        if (previewModal.hidden) {
            previousBodyOverflow = document.body.style.overflow;
        }

        previewModal.hidden = false;
        document.body.style.overflow = 'hidden';

        previewClose.focus();

        const extension = getExtension(file);

        const imageTypes = ['jpg', 'jpeg', 'png', 'webp'];


        // Image preview.
        if (imageTypes.includes(extension)) {

            currentPreviewUrl = URL.createObjectURL(file);

            const image = document.createElement('img');

            image.className = 'preview-image';
            image.src = currentPreviewUrl;
            image.alt = 'Preview of ' + file.name;

            previewContent.appendChild(image);

            return;

        }


        // PDF preview.
        if (extension === 'pdf') {

            currentPreviewUrl = URL.createObjectURL(file);

            const frame = document.createElement('iframe');

            frame.className = 'preview-pdf';
            frame.src = currentPreviewUrl;
            frame.title = 'Preview of ' + file.name;

            previewContent.appendChild(frame);

            return;

        }


        // TXT preview.
        if (extension === 'txt') {

            showNotice(
                'Loading your document...',
                'Just a moment while Shelly prepares your preview.',
                'TXT'
            );

            try {

                const text = await file.text();

                if (
                    previewModal.hidden ||
                    requestId !== previewRequestId
                ) {
                    return;
                }

                const content = document.createElement('pre');

                content.className = 'preview-text';
                content.textContent = text || 'This text file is empty.';

                previewContent.replaceChildren(content);

            } catch (error) {

                if (requestId !== previewRequestId) {
                    return;
                }

                showNotice(
                    'Preview unavailable',
                    'Shelly could not read this text file in the browser.',
                    'TXT'
                );

            }

            return;

        }


        // DOCX preview notice.
        if (extension === 'docx') {

            showNotice(
                'Document selected!',
                'Your DOCX document is ready to upload. Browser preview is not available for this file type, but Shelly can process it after uploading.',
                'DOC'
            );

            return;

        }


        // PPTX preview notice.
        if (extension === 'pptx') {

            showNotice(
                'Presentation selected!',
                'Your PowerPoint presentation is ready to upload. Shelly can process it after uploading. Browser preview is not available for this file type.',
                'PPT'
            );

            return;

        }


        showNotice(
            'Preview unavailable',
            'This file type cannot be previewed in the browser.',
            'FILE'
        );

    }


    // =====================================
    // RENDER SELECTED FILES
    // =====================================

    function renderFiles() {

        const count = selectedFiles.length;
        const totalSize = getTotalSize();

        fileCount.textContent = count === 0
            ? '0 files selected'
            : count === 1
                ? '1 file selected'
                : count + ' files selected';

        selectionLimit.textContent = count + ' / ' + MAX_FILES;

        fileSelection.hidden = count === 0;

        addFilesButton.disabled = count >= MAX_FILES;

        fileList.replaceChildren();


        selectedFiles.forEach(function (file, index) {

            const chip = document.createElement('div');
            chip.className = 'file-chip';


            // File type icon.
            const icon = document.createElement('div');
            icon.className = 'file-chip-icon';

            const extension = getExtension(file);

            if (['jpg', 'jpeg', 'png', 'webp'].includes(extension)) {
                icon.textContent = 'IMG';
            } else if (extension === 'pdf') {
                icon.textContent = 'PDF';
            } else if (extension === 'docx') {
                icon.textContent = 'DOC';
            } else if (extension === 'pptx') {
                icon.textContent = 'PPT';
            } else {
                icon.textContent = 'TXT';
            }


            // File details.
            const details = document.createElement('div');
            details.className = 'file-chip-details';

            const label = document.createElement('span');
            label.className = 'file-chip-label';
            label.textContent = file.name;
            label.title = file.name;

            const size = document.createElement('span');
            size.className = 'file-chip-size';
            size.textContent = formatFileSize(file.size);

            details.appendChild(label);
            details.appendChild(size);


            // Actions.
            const actions = document.createElement('div');
            actions.className = 'file-chip-actions';


            // Preview.
            const previewButton = document.createElement('button');

            previewButton.type = 'button';
            previewButton.className = 'file-chip-preview';
            previewButton.textContent = 'Preview';

            previewButton.setAttribute(
                'aria-label',
                'Preview ' + file.name
            );

            previewButton.addEventListener('click', function () {
                previewFile(file, previewButton);
            });


            // Remove.
            const removeButton = document.createElement('button');

            removeButton.type = 'button';
            removeButton.className = 'file-chip-remove';
            removeButton.textContent = '×';

            removeButton.setAttribute(
                'aria-label',
                'Remove ' + file.name
            );

            removeButton.addEventListener('click', function () {

                if (
                    !previewModal.hidden &&
                    currentPreviewFile === file
                ) {
                    closePreview();
                }

                selectedFiles.splice(index, 1);

                updateInputFiles();
                renderFiles();

                clearLimitMessage();

            });


            actions.appendChild(previewButton);
            actions.appendChild(removeButton);

            chip.appendChild(icon);
            chip.appendChild(details);
            chip.appendChild(actions);

            fileList.appendChild(chip);

        });

    }


    // =====================================
    // ADD AND VALIDATE FILES
    // =====================================

    function addSelectedFiles(files) {

        clearLimitMessage();

        const incomingFiles = Array.from(files || []);

        incomingFiles.forEach(function (file) {

            const extension = getExtension(file);


            // File type.
            if (!ALLOWED_EXTENSIONS.includes(extension)) {

                showLimitMessage(
                    file.name + ' is not supported. Please choose PDF, TXT, DOCX, PPTX, JPG, PNG, or WEBP files.'
                );

                return;

            }


            // Duplicate check.
            const isDuplicate = selectedFiles.some(function (selectedFile) {

                return (
                    selectedFile.name === file.name &&
                    selectedFile.size === file.size &&
                    selectedFile.lastModified === file.lastModified
                );

            });

            if (isDuplicate) {

                showLimitMessage(
                    file.name + ' has already been selected.'
                );

                return;

            }


            // Individual file size.
            if (file.size > MAX_FILE_SIZE) {

                showLimitMessage(
                    file.name + ' is larger than 25 MB. Please choose a smaller file.'
                );

                return;

            }


            // File count.
            if (selectedFiles.length >= MAX_FILES) {

                showLimitMessage(
                    'You can select up to 10 files at a time. Remove a file before adding another.'
                );

                return;

            }


            // Combined file size.
            const newTotalSize = getTotalSize() + file.size;

            if (newTotalSize > MAX_TOTAL_SIZE) {

                showLimitMessage(
                    'Your selected files would exceed the combined 128 MB upload limit.'
                );

                return;

            }


            selectedFiles.push(file);

        });


        updateInputFiles();
        renderFiles();

    }


    // =====================================
    // FILE INPUT EVENTS
    // =====================================

    fileInput.addEventListener('click', function () {
        this.value = '';
    });


    fileInput.addEventListener('change', function () {
        addSelectedFiles(this.files);
    });


    addFilesButton.addEventListener('click', function () {

        if (selectedFiles.length < MAX_FILES) {
            fileInput.click();
        }

    });


    // =====================================
    // DRAG AND DROP
    // =====================================

    ['dragenter', 'dragover'].forEach(function (eventName) {

        fileDrop.addEventListener(eventName, function (event) {

            event.preventDefault();
            event.stopPropagation();

            fileDrop.classList.add('drag-active');

        });

    });


    ['dragleave', 'dragend'].forEach(function (eventName) {

        fileDrop.addEventListener(eventName, function (event) {

            event.preventDefault();
            event.stopPropagation();

            fileDrop.classList.remove('drag-active');

        });

    });


    fileDrop.addEventListener('drop', function (event) {

        event.preventDefault();
        event.stopPropagation();

        fileDrop.classList.remove('drag-active');

        if (event.dataTransfer && event.dataTransfer.files) {
            addSelectedFiles(event.dataTransfer.files);
        }

    });


    fileDrop.addEventListener('keydown', function (event) {

        if (event.key === 'Enter' || event.key === ' ') {

            event.preventDefault();
            fileInput.click();

        }

    });


    // =====================================
    // PREVIEW MODAL EVENTS
    // =====================================

    previewClose.addEventListener('click', closePreview);

    previewBackdrop.addEventListener('click', closePreview);


    document.addEventListener('keydown', function (event) {

        if (previewModal.hidden) {
            return;
        }

        if (event.key === 'Escape') {

            event.preventDefault();
            closePreview();

            return;

        }

        if (event.key === 'Tab') {

            event.preventDefault();
            previewClose.focus();

        }

    });


    // =====================================
    // FORM SUBMISSION
    // =====================================

    uploadForm.addEventListener('submit', function (event) {

        clearLimitMessage();


        // At least one file.
        if (selectedFiles.length === 0) {

            event.preventDefault();

            showLimitMessage(
                'Please select at least one study material before creating your study set.'
            );

            fileDrop.focus();

            return;

        }


        // Maximum file count.
        if (selectedFiles.length > MAX_FILES) {

            event.preventDefault();

            showLimitMessage(
                'You can upload a maximum of 10 files at a time.'
            );

            return;

        }


        // Individual file size.
        const oversizedFile = selectedFiles.find(function (file) {
            return file.size > MAX_FILE_SIZE;
        });

        if (oversizedFile) {

            event.preventDefault();

            showLimitMessage(
                oversizedFile.name + ' exceeds the 25 MB per-file limit.'
            );

            return;

        }


        // Combined size.
        if (getTotalSize() > MAX_TOTAL_SIZE) {

            event.preventDefault();

            showLimitMessage(
                'Your selected files exceed the combined 128 MB upload limit.'
            );

            return;

        }


        // Make sure the actual input contains every selected file.
        updateInputFiles();

        // Prevent accidental repeated submissions.
        if (uploadButton) {

            uploadButton.disabled = true;

            uploadButton.querySelector('span:first-child').textContent =
                'Uploading your materials...';

        }

    });


    // =====================================
    // INITIAL STATE
    // =====================================

    renderFiles();

});
</script>

@endsection
