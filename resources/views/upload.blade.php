
@extends('layouts.app')

@section('title', 'Upload Materials')

@section('content')

<div class="upload-page">

    <div class="upload-decoration butterfly butterfly-one">🦋</div>
    <div class="upload-decoration butterfly butterfly-two">🦋</div>
    <div class="upload-decoration bow bow-one">୨୧</div>
    <div class="upload-decoration bow bow-two">୨୧</div>

    <section class="upload-layout">

        <div class="upload-intro">

            <div class="intro-label">
                <span>✿</span>
                YOUR LITTLE STUDY SPACE
            </div>

            <h1>
                Make studying<br>
                feel a little <span>sweeter.</span>
            </h1>

            <p class="intro-description">
                Welcome to Shelly's cozy little study corner.
                Upload your learning materials and turn them
                into summaries, flashcards, and fun quizzes.
                One little step at a time.
            </p>

            <div class="intro-features">
                <div><span>♡</span> Simple study tools</div>
                <div><span>♡</span> Learn at your own pace</div>
                <div><span>♡</span> Your personal study space</div>
            </div>

            <div class="reminder-card">
                <div class="reminder-bunny">🐰</div>

                <div>
                    <strong>A little reminder</strong>
                    <p>You're doing lovely. One page at a time!</p>
                </div>

                <span class="reminder-bow">୨୧</span>
            </div>

        </div>

        <section class="upload-card">

            <div class="card-ribbon">🎀</div>

            <div class="upload-heading">
                <span class="section-label">LET'S GET STARTED</span>

                <h2>Add your study materials</h2>

                <p>
                    Choose one or more files, preview them,
                    and let Shelly help make studying easier.
                </p>
            </div>

            <div class="divider-decoration">
                <span>♡</span>
                <span>✿</span>
                <span>♡</span>
            </div>

            @if ($errors->any())
                <div class="error-message" role="alert">
                    <strong>Oopsie! Please check your information.</strong>

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
                id="upload-form"
            >
                @csrf

                {{-- Study set title --}}
                <div class="study-title-field">

                    <label for="study-title">
                        Give your study set a name
                    </label>

                    <p>
                        Choose a title that helps you recognize these materials later.
                    </p>

                    <input
                        type="text"
                        id="study-title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="e.g. Nursing - Chapter 1"
                        maxlength="255"
                        required
                    >

                    @error('title')
                        <span class="title-error">{{ $message }}</span>
                    @enderror

                </div>

                {{-- File picker --}}
                <div class="file-picker-wrapper">

                    <input
                        type="file"
                        id="study-file"
                        name="files[]"
                        accept=".pdf,.txt,.docx,.pptx,.jpg,.jpeg,.png,.webp"
                        multiple
                        hidden
                    >

                    <label class="file-drop" for="study-file">

                        <div class="upload-icon">♡</div>

                        <span class="file-title">
                            Drop your files here
                        </span>

                        <span class="file-description">
                            PDF, TXT, DOCX, PPTX, JPG, PNG, WEBP
                        </span>

                        <span class="browse-button">
                            <span>＋</span>
                            Browse files
                        </span>

                        <span class="selected-file" id="file-count">
                            No files selected yet
                        </span>

                    </label>

                    {{-- Selected files --}}
                    <div class="file-selection" id="file-selection" hidden>

                        <div class="selection-heading">
                            <span class="selection-label">
                                YOUR SELECTED FILES
                            </span>

                            <span class="selection-limit" id="selection-limit">
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

                    <p
                        class="file-limit-message"
                        id="file-limit-message"
                        role="alert"
                        hidden
                    ></p>

                </div>

                <button class="button upload-button" type="submit">
                    Upload my materials
                    <span>✧</span>
                </button>

            </form>

            <div class="upload-card-footer">
                <span>🎀</span>
                Your study corner, one topic at a time.
                <span>♡</span>
            </div>

        </section>

    </section>

</div>


{{-- FILE PREVIEW MODAL --}}
<div class="preview-modal" id="preview-modal" hidden>

    <div
        class="preview-backdrop"
        id="preview-backdrop"
    ></div>

    <div
        class="preview-window"
        role="dialog"
        aria-modal="true"
        aria-labelledby="preview-title"
    >

        <div class="preview-header">

            <div>
                <span class="section-label">
                    YOUR STUDY MATERIAL
                </span>

                <h3 id="preview-title">
                    File Preview
                </h3>
            </div>

            <button
                type="button"
                class="preview-close"
                id="preview-close"
                aria-label="Close preview"
            >
                ×
            </button>

        </div>

        <div
            class="preview-content"
            id="preview-content"
        ></div>

        <div class="preview-footer">
            <span>♡</span>
            Your file stays private until you upload it.
        </div>

    </div>

</div>


<style>

/* =========================================
   UPLOAD PAGE
========================================= */

.upload-page {
    position: relative;
    isolation: isolate;
    width: 100%;
    padding: 38px 0 25px;
    color: var(--ink);
    font-family: "Nunito", sans-serif;
}

.upload-layout {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1.1fr) minmax(340px, .9fr);
    align-items: center;
    gap: clamp(35px, 6vw, 80px);
    width: min(1080px, 100%);
    margin: 0 auto;
    padding: 0 20px;
    box-sizing: border-box;
}


/* =========================================
   WELCOME SECTION
========================================= */

.upload-intro {
    position: relative;
    padding: 25px 0;
    transform: translateY(-20px);
}

.intro-label {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 20px;
    color: var(--pink-dark);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 2px;
}

.intro-label span {
    font-size: 19px;
}

.upload-intro h1 {
    margin: 0;
    color: #755267;
    font-family: "Playfair Display", serif;
    font-size: clamp(38px, 4.5vw, 57px);
    font-weight: 600;
    line-height: 1.17;
    letter-spacing: -1.5px;
}

.upload-intro h1 span {
    color: #d982a6;
}

.intro-description {
    max-width: 480px;
    margin: 22px 0 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.9;
}

.intro-features {
    display: flex;
    flex-wrap: wrap;
    gap: 13px 19px;
    margin-top: 27px;
}

.intro-features div {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #927486;
    font-size: 12px;
    font-weight: 800;
}

.intro-features span {
    color: #d982a6;
    font-size: 17px;
}

.reminder-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 13px;
    width: fit-content;
    max-width: 100%;
    margin-top: 38px;
    padding: 14px 19px;
    border: 1px solid #f0dce4;
    border-radius: 18px;
    background: rgba(255, 255, 255, .75);
}

.reminder-bunny {
    font-size: 28px;
}

.reminder-card strong {
    color: #a65f7b;
    font-family: "Playfair Display", serif;
    font-size: 14px;
}

.reminder-card p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
}

.reminder-bow {
    margin-left: 12px;
    color: #d98eaa;
    font-size: 25px;
}



/* =========================================
   UPLOAD PAGE
========================================= */

.upload-page {
    position: relative;
    isolation: isolate;
    width: 100%;
    min-width: 0;
    padding: 38px 0 25px;
    color: var(--ink);
    font-family: "Nunito", sans-serif;
    box-sizing: border-box;
}

.upload-layout {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
    align-items: center;
    gap: clamp(35px, 6vw, 80px);
    width: min(1080px, 100%);
    min-width: 0;
    margin: 0 auto;
    padding: 0 20px;
    box-sizing: border-box;
}

/* =========================================
   WELCOME SECTION
========================================= */

.upload-intro {
    position: relative;
    min-width: 0;
    padding: 25px 0;
    transform: translateY(-20px);
}

.intro-label {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 20px;
    color: var(--pink-dark);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 2px;
}

.intro-label span {
    font-size: 19px;
}

.upload-intro h1 {
    margin: 0;
    color: #755267;
    font-family: "Playfair Display", serif;
    font-size: clamp(38px, 4.5vw, 57px);
    font-weight: 600;
    line-height: 1.17;
    letter-spacing: -1.5px;
}

.upload-intro h1 span {
    color: #d982a6;
}

.intro-description {
    max-width: 480px;
    margin: 22px 0 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.9;
}

.intro-features {
    display: flex;
    flex-wrap: wrap;
    gap: 13px 19px;
    margin-top: 27px;
}

.intro-features div {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #927486;
    font-size: 12px;
    font-weight: 800;
}

.intro-features span {
    color: #d982a6;
    font-size: 17px;
}

.reminder-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 13px;
    width: fit-content;
    max-width: 100%;
    margin-top: 38px;
    padding: 14px 19px;
    border: 1px solid #f0dce4;
    border-radius: 18px;
    background: rgba(255, 255, 255, .75);
}

.reminder-bunny {
    font-size: 28px;
}

.reminder-card strong {
    color: #a65f7b;
    font-family: "Playfair Display", serif;
    font-size: 14px;
}

.reminder-card p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
}

.reminder-bow {
    margin-left: 12px;
    color: #d98eaa;
    font-size: 25px;
}

/* =========================================
   UPLOAD CARD
========================================= */

.upload-card {
    position: relative;
    width: 100%;
    min-width: 0;
    max-width: 100%;
    box-sizing: border-box;
    padding: 35px;
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 27px;
    background: rgba(255, 253, 251, .96);
    box-shadow: var(--shadow);
}

.card-ribbon {
    position: absolute;
    top: 19px;
    right: 22px;
    display: grid;
    place-items: center;
    width: 43px;
    height: 43px;
    border-radius: 15px;
    background: var(--pink-light);
    font-size: 22px;
    transform: rotate(8deg);
}

.upload-heading {
    padding-right: 25px;
}

.section-label {
    color: var(--pink-dark);
    font-size: 10px;
    font-weight: 900;
    letter-spacing: 2px;
}

.upload-heading h2 {
    margin: 12px 0 8px;
    color: #755267;
    font-family: "Playfair Display", serif;
    font-size: 25px;
    font-weight: 600;
    line-height: 1.3;
}

.upload-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.divider-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    margin: 24px 0;
    color: #d98eaa;
    font-size: 15px;
}

.divider-decoration::before,
.divider-decoration::after {
    content: "";
    flex: 1;
    height: 1px;
    background: var(--line);
}

/* =========================================
   UPLOAD FORM AND TITLE
========================================= */

.upload-form {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 17px;
    width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.study-title-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.study-title-field label {
    color: #805d72;
    font-size: 12px;
    font-weight: 900;
}

.study-title-field p {
    margin: 0 0 3px;
    color: #a88d9b;
    font-size: 11px;
    line-height: 1.6;
}

.study-title-field input {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    min-height: 47px;
    padding: 12px 14px;
    border: 1px solid #edcbd9;
    border-radius: 13px;
    outline: none;
    background: #fffafd;
    color: #755267;
    font-family: "Nunito", sans-serif;
    font-size: 12px;
    transition: .2s ease;
}

.study-title-field input::placeholder {
    color: #c2a8b5;
}

.study-title-field input:focus {
    border-color: #d982a6;
    box-shadow: 0 0 0 3px rgba(217, 130, 166, .13);
}

.title-error {
    color: #a34e65;
    font-size: 11px;
}

/* =========================================
   FILE PICKER
========================================= */

.file-picker-wrapper {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    min-width: 0;
    max-width: 100%;
}

.file-drop {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    min-width: 0;
    max-width: 100%;
    min-height: 205px;
    padding: 23px 16px;
    box-sizing: border-box;
    border: 2px dashed #e8b8cc;
    border-radius: 21px;
    background:
        radial-gradient(circle at 10% 15%, #fff0f6 0, transparent 28%),
        #fff8fb;
    color: #89677a;
    text-align: center;
    cursor: pointer;
    transition: .2s ease;
}

.file-drop:hover {
    border-color: #d98eaa;
    background-color: #fff0f6;
    transform: translateY(-2px);
}

.file-drop:focus-within {
    outline: 3px solid #f3c5d6;
    outline-offset: 3px;
}

.upload-icon {
    display: grid;
    place-items: center;
    width: 53px;
    height: 53px;
    border-radius: 18px;
    background: #ffe8f1;
    color: #d27fa2;
    font-size: 29px;
}

.file-title {
    color: #805d72;
    font-family: "Playfair Display", serif;
    font-size: 16px;
    font-weight: 600;
}

.file-description {
    color: #a88d9b;
    font-size: 11px;
}

.browse-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-top: 3px;
    padding: 9px 17px;
    border: 1px solid #edcbd9;
    border-radius: 30px;
    background: white;
    color: var(--pink-dark);
    font-size: 11px;
    font-weight: 900;
}

.browse-button span {
    font-size: 17px;
}

.selected-file {
    max-width: 100%;
    color: #a88d9b;
    font-size: 11px;
    font-weight: 800;
}

/* =========================================
   SELECTED FILES
========================================= */

.file-selection {
    width: 100%;
    min-width: 0;
    max-width: 100%;
    padding: 14px;
    box-sizing: border-box;
    border: 1px solid #f0dce4;
    border-radius: 16px;
    background: #fffafd;
}

.file-selection[hidden] {
    display: none;
}

.selection-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 11px;
}

.selection-label {
    color: #a16f88;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 1.4px;
}

.selection-limit {
    color: #b58a9e;
    font-size: 10px;
    font-weight: 800;
}

.selected-file-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
    min-width: 0;
}

.file-chip {
    display: flex;
    align-items: center;
    gap: 9px;
    width: 100%;
    min-width: 0;
    max-width: 100%;
    padding: 9px 10px;
    box-sizing: border-box;
    border: 1px solid #f1dce5;
    border-radius: 13px;
    background: white;
    color: #916c80;
}

.file-chip-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #fff0f5;
    color: #c57697;
    font-size: 12px;
    font-weight: 900;
}

.file-chip-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
    overflow: hidden;
}

.file-chip-label {
    overflow: hidden;
    color: #916c80;
    font-size: 11px;
    font-weight: 900;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.file-chip-size {
    color: #b58a9e;
    font-size: 10px;
}

.file-chip-actions {
    display: flex;
    align-items: center;
    flex-shrink: 0;
    gap: 6px;
}

.file-chip-preview {
    padding: 6px 10px;
    border: 1px solid #edcbd9;
    border-radius: 20px;
    background: #fff8fb;
    color: #a65f7b;
    font-family: inherit;
    font-size: 10px;
    font-weight: 900;
    cursor: pointer;
}

.file-chip-preview:hover {
    background: #ffeaf2;
}

.file-chip-remove {
    display: grid;
    place-items: center;
    width: 25px;
    height: 25px;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: #fff0f5;
    color: #c57697;
    font-size: 16px;
    cursor: pointer;
    transition: .2s ease;
}

.file-chip-remove:hover {
    background: #f7dce7;
    color: #a94f77;
}

.add-files-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 13px;
    padding: 7px 12px;
    border: 1px solid #edcbd9;
    border-radius: 30px;
    background: white;
    color: var(--pink-dark);
    font-family: inherit;
    font-size: 10px;
    font-weight: 900;
    cursor: pointer;
    transition: .2s ease;
}

.add-files-button:hover {
    background: #fff0f6;
    border-color: #d98eaa;
}

.add-files-button span {
    font-size: 15px;
}

.add-files-button:disabled {
    opacity: .45;
    cursor: not-allowed;
}

.file-limit-message {
    margin: -5px 0 0;
    padding: 10px 12px;
    border: 1px solid #efc2ce;
    border-radius: 10px;
    background: #fff0f3;
    color: #a34e65;
    font-size: 11px;
    line-height: 1.6;
    text-align: center;
}

.file-limit-message[hidden] {
    display: none;
}

/* =========================================
   PREVIEW MODAL
========================================= */

.preview-modal[hidden] {
    display: none;
}

.preview-modal {
    position: fixed;
    z-index: 9999;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 20px;
}

.preview-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(63, 39, 54, .58);
    backdrop-filter: blur(4px);
}

.preview-window {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    width: min(850px, 100%);
    max-height: 90vh;
    overflow: hidden;
    border: 1px solid #f0dce4;
    border-radius: 22px;
    background: #fffdfc;
    box-shadow: 0 20px 70px rgba(63, 39, 54, .2);
}

.preview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 22px;
    border-bottom: 1px solid #f0dce4;
}

.preview-header h3 {
    margin: 5px 0 0;
    color: #755267;
    font-family: "Playfair Display", serif;
    font-size: 21px;
    overflow-wrap: anywhere;
}

.preview-close {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border: 1px solid #f0dce4;
    border-radius: 50%;
    background: #fff5f9;
    color: #a65f7b;
    font-size: 25px;
    cursor: pointer;
}

.preview-content {
    min-height: 250px;
    overflow: auto;
    padding: 18px;
    background: #fff8fb;
}

.preview-image {
    display: block;
    max-width: 100%;
    max-height: 65vh;
    margin: auto;
    object-fit: contain;
    border-radius: 8px;
}

.preview-pdf {
    display: block;
    width: 100%;
    height: 65vh;
    border: 0;
    border-radius: 10px;
    background: white;
}

.preview-text {
    max-height: 65vh;
    overflow: auto;
    margin: 0;
    padding: 20px;
    border: 1px solid #f0dce4;
    border-radius: 12px;
    background: white;
    color: #70596a;
    font-family: "Nunito", sans-serif;
    font-size: 13px;
    line-height: 1.8;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.preview-notice {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 250px;
    padding: 25px;
    color: #916c80;
    text-align: center;
}

.preview-notice-icon {
    display: grid;
    place-items: center;
    width: 65px;
    height: 65px;
    border-radius: 20px;
    background: #ffe8f1;
    color: #c57697;
    font-size: 29px;
}

.preview-notice strong {
    color: #805d72;
    font-family: "Playfair Display", serif;
    font-size: 19px;
}

.preview-notice p {
    max-width: 380px;
    margin: 0;
    color: #a88d9b;
    font-size: 12px;
    line-height: 1.7;
}

.preview-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 13px;
    border-top: 1px solid #f0dce4;
    color: #a88d9b;
    font-size: 10px;
}

.preview-footer span {
    color: #d98eaa;
}

/* =========================================
   UPLOAD BUTTON
========================================= */

.upload-button {
    width: 100%;
    min-width: 0;
    min-height: 53px;
    gap: 10px;
    border-radius: 15px;
    background: linear-gradient(110deg, #e99bb9, #d982a6);
    box-shadow: 0 8px 18px rgba(216, 127, 166, .16);
}

.upload-button:hover {
    background: linear-gradient(110deg, #df8eaf, #c76d97);
    box-shadow: 0 10px 22px rgba(216, 127, 166, .22);
}

.upload-button span {
    font-size: 19px;
}

.upload-card-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    margin-top: 21px;
    color: #a88d9b;
    font-size: 10px;
}

.upload-card-footer span {
    color: #d98eaa;
}

/* =========================================
   FEEDBACK MESSAGES
========================================= */

.upload-page .error-message,
.upload-page .success-message {
    margin: 15px 0;
    padding: 13px 16px;
    border-radius: 13px;
    font-size: 12px;
    line-height: 1.6;
}

.upload-page .error-message {
    border: 1px solid #efc2ce;
    background: #fff0f3;
    color: #a34e65;
}

.upload-page .error-message ul {
    margin: 6px 0 0;
    padding-left: 19px;
}

.upload-page .success-message {
    border: 1px solid #d9e9d8;
    background: #f4fbf2;
    color: #668d65;
}

/* =========================================
   DECORATIVE ELEMENTS
========================================= */

.upload-decoration {
    position: absolute;
    z-index: 0;
    pointer-events: none;
    user-select: none;
}

.butterfly-one {
    top: 15px;
    left: 3%;
    transform: rotate(-17deg);
}

.butterfly-two {
    right: 3%;
    bottom: 70px;
    transform: rotate(15deg);
}

.upload-page .butterfly {
    font-size: 25px;
    opacity: .65;
}

.bow-one {
    top: 42%;
    left: -5px;
    transform: rotate(-15deg);
}

.bow-two {
    right: 3%;
    top: 13%;
    transform: rotate(12deg);
}

.upload-page .bow {
    color: #e4a0ba;
    font-size: 36px;
    opacity: .65;
}

/* =========================================
   RESPONSIVE
========================================= */

/* =========================================
   IPAD LANDSCAPE OPTIMIZATION
========================================= */

@media (min-width: 851px) and (max-width: 1100px) {

    .upload-layout {
        grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
        gap: 28px;
        width: 100%;
        max-width: 1000px;
        padding: 0 25px;
    }

    .upload-intro {
        min-width: 0;
    }

    .upload-intro h1 {
        font-size: clamp(36px, 4vw, 48px);
    }

    .intro-description {
        font-size: 13px;
    }

    .upload-card {
        min-width: 0;
        padding: 30px;
    }

    .file-drop {
        min-height: 195px;
    }

    .file-chip-preview,
    .file-chip-remove,
    .preview-close {
        min-height: 36px;
    }

    .file-chip-remove {
        min-width: 36px;
    }
}

@media (max-width: 850px) {

    .upload-page {
        width: 100%;
        max-width: 100%;
        padding: 25px 0;
        box-sizing: border-box;
    }

    .upload-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        width: 100%;
        max-width: 570px;
        min-width: 0;
        gap: 25px;
        padding: 0 15px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .upload-intro {
        padding: 12px 5px;
        transform: none;
    }

    .upload-intro h1 {
        font-size: clamp(39px, 7vw, 52px);
    }

    .upload-intro,
    .upload-card {
        width: 100%;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }

    .upload-form,
    .study-title-field,
    .file-picker-wrapper,
    .file-drop,
    .file-selection,
    .selected-file-list {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }
}

@media (max-width: 480px) {

    .upload-page {
        padding-top: 15px;
        overflow-x: clip;
    }

    .upload-layout {
        width: 100%;
        min-width: 0;
        padding: 0 12px;
    }

    .upload-intro h1 {
        font-size: 38px;
    }

    .intro-description {
        font-size: 13px;
    }

    .intro-features {
        gap: 11px;
    }

    .intro-features div {
        font-size: 11px;
    }

    .upload-card {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        padding: 25px 19px;
        border-radius: 23px;
    }

    .upload-form,
    .study-title-field,
    .file-picker-wrapper,
    .file-selection,
    .selected-file-list,
    .file-chip {
        width: 100%;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }

    .upload-heading h2 {
        font-size: 22px;
    }

    .file-drop {
        min-height: 190px;
    }

    .file-chip-details {
        min-width: 0;
    }

    .bow-one,
    .bow-two,
    .butterfly-two {
        display: none;
    }

    .preview-modal {
        padding: 10px;
    }

    .preview-window {
        max-height: 95vh;
    }

    .preview-header {
        padding: 14px;
    }

    .preview-content {
        padding: 10px;
    }

    .preview-pdf {
        height: 65vh;
    }
}

@media (prefers-reduced-motion: reduce) {

    .upload-page *,
    .upload-page *::before,
    .upload-page *::after {
        transition: none !important;
    }
}


@media (prefers-reduced-motion: reduce) {

    .upload-page *,
    .upload-page *::before,
    .upload-page *::after {
        transition: none !important;
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
        !uploadForm
    ) {
        return;
    }


    // =====================================
    // UPLOAD LIMITS
    // =====================================

    const MAX_FILES = 10;

    // 25 MB per file.
    const MAX_FILE_SIZE = 25 * 1024 * 1024;

    // 128 MB combined for all selected files.
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
    // FILE SIZE HELPERS
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
                'Just a little moment while Shelly prepares your preview.',
                '♡'
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
                    '♡'
                );

            }

            return;

        }


        // DOCX preview.
        if (extension === 'docx') {

            showNotice(
                'Document selected!',
                'Your DOCX document is ready to upload. Browser preview is not available for this file type, but Shelly can process it after uploading.',
                '📄'
            );

            return;

        }


        // PPTX preview.
        if (extension === 'pptx') {

            showNotice(
                'Presentation selected!',
                'Your PowerPoint presentation is ready to upload. Shelly will extract its slide text after uploading. Browser preview is not available for this file type.',
                '📊'
            );

            return;

        }


        showNotice(
            'Preview unavailable',
            'This file type cannot be previewed in the browser.',
            '♡'
        );

    }


    // =====================================
    // RENDER SELECTED FILES
    // =====================================

    function renderFiles() {

        const count = selectedFiles.length;
        const totalSize = getTotalSize();

        fileCount.textContent = count === 0
            ? 'No files selected yet'
            : count === 1
                ? '1 file selected · ' + formatFileSize(totalSize)
                : count + ' files selected · ' + formatFileSize(totalSize);

        selectionLimit.textContent = count + ' / ' + MAX_FILES;

        fileSelection.hidden = count === 0;

        addFilesButton.disabled = count >= MAX_FILES;

        fileList.replaceChildren();


        selectedFiles.forEach(function (file, index) {

            const chip = document.createElement('div');
            chip.className = 'file-chip';


            // File icon.
            const icon = document.createElement('div');
            icon.className = 'file-chip-icon';

            const extension = getExtension(file);

            if (['jpg', 'jpeg', 'png', 'webp'].includes(extension)) {
                icon.textContent = '♡';
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


            // File actions.
            const actions = document.createElement('div');
            actions.className = 'file-chip-actions';


            // Preview button.
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


            // Remove button.
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

                limitMessage.hidden = true;

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

        limitMessage.hidden = true;

        const incomingFiles = Array.from(files || []);

        let addedCount = 0;

        incomingFiles.forEach(function (file) {

            const extension = getExtension(file);


            // Check supported file types.
            if (!ALLOWED_EXTENSIONS.includes(extension)) {

                showLimitMessage(
                    file.name + ' is not a supported file type. Please choose PDF, TXT, DOCX, PPTX, JPG, PNG, or WEBP files.'
                );

                return;

            }

            // Check for duplicate files.
const isDuplicate = selectedFiles.some(function (selectedFile) {
    return (
        selectedFile.name === file.name &&
        selectedFile.size === file.size &&
        selectedFile.lastModified === file.lastModified
    );
});

if (isDuplicate) {
    showLimitMessage(
        file.name + ' has already been selected. Please choose a different file.'
    );

    return;
}
            // Check individual file size.
            if (file.size > MAX_FILE_SIZE) {

                showLimitMessage(
                    file.name + ' is larger than 25 MB. Please choose a smaller file.'
                );

                return;

            }


            // Check maximum number of files.
            if (selectedFiles.length >= MAX_FILES) {

                showLimitMessage(
                    'You can select up to 10 files at a time. Remove a file before adding another.'
                );

                return;

            }


            // Check combined upload size.
            const newTotalSize = getTotalSize() + file.size;

            if (newTotalSize > MAX_TOTAL_SIZE) {

                showLimitMessage(
                    'Your selected files would exceed the combined 128 MB upload limit. Remove a file or choose smaller files.'
                );

                return;

            }


            selectedFiles.push(file);

            addedCount++;

        });


        updateInputFiles();
        renderFiles();

        if (addedCount > 0 && limitMessage.hidden === false) {
            // Keep any warning visible if another selected file was rejected.
        }

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
    // MODAL EVENTS
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

        limitMessage.hidden = true;


        // At least one file is required.
        if (selectedFiles.length === 0) {

            event.preventDefault();

            showLimitMessage(
                'Please select at least one study material before uploading.'
            );

            fileInput.focus();

            return;

        }


        // Final file-count check.
        if (selectedFiles.length > MAX_FILES) {

            event.preventDefault();

            showLimitMessage(
                'You can upload a maximum of 10 files at a time.'
            );

            return;

        }


        // Final per-file size check.
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


        // Final combined size check.
        if (getTotalSize() > MAX_TOTAL_SIZE) {

            event.preventDefault();

            showLimitMessage(
                'Your selected files exceed the combined 128 MB upload limit.'
            );

            return;

        }


        // Synchronize the files before sending the form.
        updateInputFiles();

    });


    // =====================================
    // INITIAL STATE
    // =====================================

    renderFiles();

});
</script>

@endsection
