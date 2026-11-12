
@extends('layouts.app')

@section('title', $set->title)

@section('content')

<div class="study-page">

    {{-- SUCCESS AND ERROR MESSAGES --}}
    @if (session('success'))
        <div class="study-alert success-alert">
            <span class="alert-icon">✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="study-alert error-alert">
            <span class="alert-icon">!</span>
            {{ $errors->first() }}
        </div>
    @endif


    {{-- WELCOME BANNER --}}
    <section class="study-welcome">

        <div class="welcome-decoration decoration-top">୨୧</div>
        <div class="welcome-decoration decoration-bottom">✧</div>

        <div class="welcome-content">

            <span class="page-kicker">
                YOUR PERSONAL STUDY LIBRARY
            </span>

            <h1>{{ $set->title }}</h1>

            <p>
                Your learning space is ready.
                Choose a study tool and make every study session count.
            </p>

            <div class="welcome-actions">

                <a href="{{ route('sets.edit', $set) }}" class="edit-button">
                    Edit Materials
                </a>

                <button
                    type="button"
                    class="delete-button"
                    onclick="openDeleteModal()"
                >
                    Delete Study Set
                </button>

            </div>

        </div>

        <div class="welcome-illustration" aria-hidden="true">
            <div class="illustration-paper">
                <span class="paper-decoration">୨୧</span>
                <span class="paper-line"></span>
                <span class="paper-line short"></span>
                <span class="paper-line"></span>
                <span class="paper-line medium"></span>
                <span class="paper-star">✧</span>
            </div>

            <span class="illustration-sparkle sparkle-one">✧</span>
            <span class="illustration-sparkle sparkle-two">✦</span>
        </div>

    </section>


    {{-- UPLOADED MATERIALS --}}
    <section class="materials-section">

        <div class="section-heading">

            <div>
                <span class="section-kicker">YOUR STUDY COLLECTION</span>

                <h2>Uploaded Materials</h2>

                <p>
                    Your files, all in one place.
                    Review the materials you added to this study set.
                </p>
            </div>

            <div class="materials-count">
                <span class="count-number">
                    {{ $set->materials->count() }}
                </span>

                <span class="count-label">
                    {{ Str::plural('FILE', $set->materials->count()) }}
                </span>
            </div>

        </div>


        <div class="materials-list">

            @forelse ($set->materials as $material)

                <div class="material-item">

                    <div class="material-file-icon">
                        <span aria-hidden="true">▤</span>
                    </div>

                    <div class="material-details">

                        <strong title="{{ $material->original_name }}">
                            {{ $material->original_name }}
                        </strong>

                        <span>
                            {{ number_format($material->file_size / 1024, 1) }} KB
                        </span>

                    </div>

                    <span class="uploaded-badge">
                        <span class="status-dot"></span>
                        Uploaded
                    </span>

                </div>

            @empty

                <div class="empty-materials">

                    <span class="empty-icon">▤</span>

                    <strong>No materials uploaded yet</strong>

                    <p>
                        Add study materials to start building your study collection.
                    </p>

                    <a href="{{ route('sets.edit', $set) }}">
                        Edit your materials →
                    </a>

                </div>

            @endforelse

        </div>

    </section>


    {{-- STUDY TOOLS --}}
    <section class="study-tools-section">

        <div class="section-heading tools-heading">

            <div>
                <span class="section-kicker">YOUR LEARNING CORNER</span>

                <h2>What would you like to study?</h2>

                <p>
                    Choose a study tool that works best for you today.
                </p>
            </div>

            <span class="heading-bow" aria-hidden="true">୨୧</span>

        </div>


        <div class="tool-grid">


            {{-- SUMMARY --}}
            <article class="tool-card summary-card">

                <div class="tool-card-top">

                    <div class="tool-icon summary-icon">
                        <span aria-hidden="true">▤</span>
                    </div>

                    <span class="tool-number">01</span>

                </div>

                <div class="tool-card-content">

                    <span class="tool-category">
                        READ & REVIEW
                    </span>

                    <h3>Study Summary</h3>

                    <p>
                        Turn your uploaded materials into organized
                        study notes with important concepts and explanations.
                    </p>

                </div>

                <a
                    href="{{ route('sets.summary.setup', $set) }}"
                    class="tool-action"
                >
                    <span>Explore Summary</span>
                    <span class="action-arrow">→</span>
                </a>

            </article>


            {{-- FLASHCARDS --}}
            <article class="tool-card flashcard-card">

                <div class="tool-card-top">

                    <div class="tool-icon flashcard-icon">
                        <span aria-hidden="true">▱</span>
                    </div>

                    <span class="tool-number">02</span>

                </div>

                <div class="tool-card-content">

                    <span class="tool-category">
                        PRACTICE & REMEMBER
                    </span>

                    <h3>Flashcards</h3>

                    <p>
                        Strengthen your memory with interactive
                        question-and-answer cards based on your materials.
                    </p>

                </div>

                <a
    href="{{ route('sets.flashcards.create', $set) }}"
    class="tool-action"
>
    <span>Create Flashcards</span>
    <span class="action-arrow">→</span>
</a>

            </article>


            {{-- QUIZ --}}
            <article class="tool-card quiz-card">

                <div class="tool-card-top">

                    <div class="tool-icon quiz-icon">
                        <span aria-hidden="true">?</span>
                    </div>

                    <span class="tool-number">03</span>

                </div>

                <div class="tool-card-content">

                    <span class="tool-category">
                        TEST YOUR KNOWLEDGE
                    </span>

                    <h3>Quiz Time</h3>

                    <p>
                        Challenge yourself with questions about your
                        study materials and check your understanding.
                    </p>

                </div>

                <a
                    href="{{ route('sets.quiz', $set) }}"
                    class="tool-action"
                >
                    <span>Start a Quiz</span>
                    <span class="action-arrow">→</span>
                </a>

            </article>

        </div>

    </section>


    {{-- BOTTOM DECORATION --}}
    <div class="study-footer-note">
        <span class="footer-decoration">✧</span>
        <span>A little progress every day goes a long way.</span>
        <span class="footer-decoration">✧</span>
    </div>

</div>


{{-- DELETE CONFIRMATION MODAL --}}
<div
    class="modal-overlay"
    id="deleteModal"
    aria-hidden="true"
>

    <div
        class="modal-card"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
    >

        <div class="modal-decoration">୨୧</div>

        <div class="modal-icon">
            ⌫
        </div>

        <span class="modal-kicker">
            PLEASE CONFIRM
        </span>

        <h2 id="modal-title">
            Delete this study set?
        </h2>

        <p class="modal-description">
            Are you sure you want to delete
            <strong>{{ $set->title }}</strong>?
        </p>

        <p class="modal-warning">
            Its uploaded materials, flashcards, and quiz questions
            will also be deleted.
        </p>

        <div class="modal-actions">

            <button
                type="button"
                class="cancel-button"
                onclick="closeDeleteModal()"
            >
                Keep Study Set
            </button>

            <form
                method="POST"
                action="{{ route('sets.destroy', $set) }}"
            >

                @csrf
                @method('DELETE')

                <button type="submit" class="confirm-delete">
                    Yes, Delete
                </button>

            </form>

        </div>

    </div>

</div>


<style>

/* =========================================
   STUDY PAGE
========================================= */

.study-page {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    padding-bottom: 15px;
    box-sizing: border-box;
}

.study-page *,
.study-page *::before,
.study-page *::after {
    box-sizing: border-box;
}


/* =========================================
   ALERTS
========================================= */

.study-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    margin-bottom: 17px;
    border-radius: 13px;
    font-size: 12px;
    font-weight: 700;
}

.alert-icon {
    display: grid;
    place-items: center;
    width: 23px;
    height: 23px;
    flex-shrink: 0;
    border-radius: 50%;
    font-size: 12px;
}

.success-alert {
    border: 1px solid #d3e7d8;
    background: #f2faf4;
    color: #60846a;
}

.success-alert .alert-icon {
    background: #e1f1e5;
}

.error-alert {
    border: 1px solid #efccd6;
    background: #fff2f5;
    color: #a85f75;
}

.error-alert .alert-icon {
    background: #f9dfe7;
}


/* =========================================
   WELCOME BANNER
========================================= */

.study-welcome {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
    min-height: 225px;
    padding: 32px 38px;
    overflow: hidden;
    border: 1px solid #efdce4;
    border-radius: 21px;
    background:
        linear-gradient(110deg, #fff6f7 0%, #fffafb 58%, #f9edf1 100%);
    box-shadow: 0 5px 20px rgba(130, 83, 104, .035);
}

.welcome-content {
    position: relative;
    z-index: 2;
    max-width: 620px;
    min-width: 0;
}

.page-kicker,
.section-kicker {
    display: block;
    color: #bd829a;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 1.5px;
}

.study-welcome h1 {
    margin: 12px 0 9px;
    color: #765466;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(28px, 4vw, 39px);
    font-weight: 500;
    line-height: 1.2;
    overflow-wrap: anywhere;
}

.study-welcome p {
    max-width: 470px;
    margin: 0;
    color: #99838f;
    font-size: 12px;
    line-height: 1.8;
}

.welcome-decoration {
    position: absolute;
    color: #e4b2c3;
    pointer-events: none;
}

.decoration-top {
    top: 17px;
    right: 28%;
    font-size: 25px;
}

.decoration-bottom {
    bottom: 20px;
    left: 45%;
    font-size: 22px;
}

.welcome-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 22px;
}

.edit-button,
.delete-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 16px;
    border-radius: 12px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.edit-button {
    border: 1px solid #d9a8ba;
    background: #c7839c;
    color: white;
}

.edit-button:hover {
    transform: translateY(-2px);
    background: #b66f89;
    color: white;
}

.delete-button {
    border: 1px solid #eed3dc;
    background: rgba(255,255,255,.7);
    color: #a66d82;
}

.delete-button:hover {
    border-color: #dfb5c4;
    background: #fff0f4;
    transform: translateY(-2px);
}


/* =========================================
   WELCOME ILLUSTRATION
========================================= */

.welcome-illustration {
    position: relative;
    display: grid;
    place-items: center;
    width: 155px;
    height: 155px;
    flex-shrink: 0;
    margin-right: 30px;
    border: 1px solid rgba(222, 178, 194, .5);
    border-radius: 50%;
    background: rgba(255,255,255,.38);
}

.illustration-paper {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 82px;
    height: 105px;
    padding: 17px 13px;
    transform: rotate(-6deg);
    border: 1px solid #ecd2dc;
    border-radius: 8px;
    background: #fffdfc;
    box-shadow: 5px 7px 15px rgba(136, 88, 108, .10);
}

.paper-decoration {
    margin-bottom: 2px;
    color: #cf91a9;
    font-size: 16px;
    text-align: center;
}

.paper-line {
    display: block;
    height: 3px;
    width: 100%;
    border-radius: 5px;
    background: #f0dfe6;
}

.paper-line.short {
    width: 60%;
}

.paper-line.medium {
    width: 75%;
}

.paper-star {
    position: absolute;
    right: -13px;
    bottom: -12px;
    color: #d69bb0;
    font-size: 22px;
}

.illustration-sparkle {
    position: absolute;
    color: #d59bb0;
}

.sparkle-one {
    top: 15px;
    right: 13px;
    font-size: 22px;
}

.sparkle-two {
    bottom: 17px;
    left: 13px;
    font-size: 15px;
}


/* =========================================
   SECTION HEADINGS
========================================= */

.materials-section {
    margin-top: 22px;
    padding: 24px;
    border: 1px solid #f0dfe5;
    border-radius: 19px;
    background: #fffdfc;
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 19px;
}

.section-heading h2 {
    margin: 8px 0 6px;
    color: #795568;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 23px;
    font-weight: 500;
}

.section-heading p {
    margin: 0;
    color: #a18e98;
    font-size: 11px;
    line-height: 1.7;
}


/* =========================================
   MATERIAL COUNT
========================================= */

.materials-count {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    flex-shrink: 0;
    border: 1px solid #efdce5;
    border-radius: 17px;
    background: #fff3f7;
}

.count-number {
    color: #ad7088;
    font-family: Georgia, serif;
    font-size: 22px;
    line-height: 1.1;
}

.count-label {
    margin-top: 4px;
    color: #b68a9b;
    font-size: 8px;
    font-weight: 900;
    letter-spacing: .8px;
}


/* =========================================
   MATERIAL LIST
========================================= */

.materials-list {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.material-item {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
    padding: 12px 14px;
    border: 1px solid #f0e2e8;
    border-radius: 13px;
    background: #fff9fb;
    transition: .2s ease;
}

.material-item:hover {
    border-color: #e7c2d0;
    background: #fff5f8;
}

.material-file-icon {
    display: grid;
    place-items: center;
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    border: 1px solid #efd8e2;
    border-radius: 12px;
    background: #faedf2;
    color: #bd819a;
    font-family: Georgia, serif;
    font-size: 21px;
}

.material-details {
    display: flex;
    flex: 1;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.material-details strong {
    overflow: hidden;
    color: #79596b;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.material-details span {
    color: #ad9aa3;
    font-size: 10px;
}

.uploaded-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
    padding: 7px 10px;
    border: 1px solid #e8d9e8;
    border-radius: 20px;
    background: #f8f1f8;
    color: #927995;
    font-size: 9px;
    font-weight: 800;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #9dba9e;
}


/* =========================================
   EMPTY MATERIALS
========================================= */

.empty-materials {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 28px 15px;
    border: 1px dashed #e8ccd7;
    border-radius: 14px;
    background: #fff9fb;
    text-align: center;
}

.empty-icon {
    display: grid;
    place-items: center;
    width: 45px;
    height: 45px;
    margin-bottom: 10px;
    border-radius: 13px;
    background: #f9eaf0;
    color: #b77c94;
    font-size: 22px;
}

.empty-materials strong {
    color: #795568;
    font-family: Georgia, serif;
    font-size: 16px;
}

.empty-materials p {
    margin: 7px 0 12px;
    color: #a18e98;
    font-size: 11px;
}

.empty-materials a {
    color: #b87993;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
}


/* =========================================
   STUDY TOOLS SECTION
========================================= */

.study-tools-section {
    margin-top: 31px;
}

.tools-heading {
    margin-bottom: 17px;
}

.heading-bow {
    flex-shrink: 0;
    color: #d79bb0;
    font-size: 29px;
}


/* =========================================
   TOOL GRID
========================================= */

.tool-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
    width: 100%;
}


/* =========================================
   TOOL CARDS
========================================= */

.tool-card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    min-height: 275px;
    padding: 20px;
    overflow: hidden;
    border: 1px solid #efdee5;
    border-radius: 18px;
    background: #fffdfc;
    box-shadow: 0 4px 15px rgba(126, 79, 100, .035);
    transition: transform .22s ease, box-shadow .22s ease;
}

.tool-card::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    content: "";
}

.summary-card::before {
    background: #dba9bc;
}

.flashcard-card::before {
    background: #e2b89f;
}

.quiz-card::before {
    background: #bcb1df;
}

.tool-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(126, 79, 100, .09);
}


/* TOOL ICONS */

.tool-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.tool-icon {
    display: grid;
    place-items: center;
    width: 48px;
    height: 48px;
    border: 1px solid transparent;
    border-radius: 14px;
    font-family: Georgia, serif;
    font-size: 24px;
}

.summary-icon {
    border-color: #f0dce5;
    background: #fff0f5;
    color: #bb7893;
}

.flashcard-icon {
    border-color: #f0dfd5;
    background: #fcf0e9;
    color: #c18d76;
}

.quiz-icon {
    border-color: #e4e0f3;
    background: #f1effa;
    color: #9387c2;
}

.tool-number {
    color: #d6b1bf;
    font-family: Georgia, serif;
    font-size: 14px;
}


/* TOOL TEXT */

.tool-card-content {
    flex: 1;
}

.tool-category {
    display: block;
    margin-bottom: 8px;
    color: #b9879b;
    font-size: 8px;
    font-weight: 900;
    letter-spacing: 1.1px;
    line-height: 1.5;
}

.tool-card h3 {
    margin: 0 0 9px;
    color: #795568;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 19px;
    font-weight: 500;
}

.tool-card p {
    margin: 0 0 17px;
    color: #9c8993;
    font-size: 10px;
    line-height: 1.8;
}


/* TOOL ACTION */

.tool-action {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    min-height: 42px;
    padding-top: 12px;
    border-top: 1px solid #f0e3e8;
    color: #b87892;
    font-size: 10px;
    font-weight: 900;
    text-decoration: none;
    transition: color .2s ease;
}

.tool-action:hover {
    color: #8e536d;
}

.action-arrow {
    display: grid;
    place-items: center;
    width: 27px;
    height: 27px;
    border: 1px solid #eedce4;
    border-radius: 50%;
    background: #fff5f8;
    font-size: 15px;
    transition: transform .2s ease;
}

.tool-action:hover .action-arrow {
    transform: translateX(3px);
}


/* =========================================
   FOOTER NOTE
========================================= */

.study-footer-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 27px 0 10px;
    color: #a48a97;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 12px;
    font-style: italic;
    text-align: center;
}

.footer-decoration {
    color: #d7a0b3;
    font-size: 16px;
}


/* =========================================
   DELETE MODAL
========================================= */

.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(63, 43, 54, .48);
    backdrop-filter: blur(5px);
}

.modal-overlay.open {
    display: flex;
}

.modal-card {
    position: relative;
    width: min(100%, 420px);
    padding: 35px 27px;
    border: 1px solid #f0dce5;
    border-radius: 23px;
    background: #fffdfb;
    box-shadow: 0 20px 65px rgba(55, 35, 45, .2);
    text-align: center;
    animation: modalAppear .2s ease;
}

@keyframes modalAppear {
    from {
        opacity: 0;
        transform: translateY(10px) scale(.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-decoration {
    position: absolute;
    top: 16px;
    right: 20px;
    color: #dfa9bc;
    font-size: 28px;
}

.modal-icon {
    display: grid;
    place-items: center;
    width: 62px;
    height: 62px;
    margin: 0 auto 15px;
    border: 1px solid #f0dce5;
    border-radius: 19px;
    background: #fff0f5;
    color: #bd7893;
    font-size: 26px;
}

.modal-kicker {
    color: #bd8ba0;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 1.5px;
}

.modal-card h2 {
    margin: 11px 0;
    color: #795568;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 23px;
    font-weight: 500;
}

.modal-description {
    color: #998590;
    font-size: 12px;
    line-height: 1.8;
}

.modal-description strong {
    color: #795568;
}

.modal-warning {
    padding: 11px 13px;
    border: 1px solid #f0dce5;
    border-radius: 11px;
    background: #fff7f9;
    color: #a27d8d;
    font-size: 10px;
    line-height: 1.7;
}

.modal-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 24px;
}

.cancel-button,
.confirm-delete {
    min-height: 43px;
    padding: 0 17px;
    border-radius: 12px;
    font-family: inherit;
    font-size: 10px;
    font-weight: 900;
    cursor: pointer;
    transition: .2s ease;
}

.cancel-button {
    border: 1px solid #ead8e0;
    background: white;
    color: #936b7e;
}

.cancel-button:hover {
    background: #fff4f7;
}

.confirm-delete {
    border: 1px solid #bd7893;
    background: #bd7893;
    color: white;
}

.confirm-delete:hover {
    border-color: #a85e7c;
    background: #a85e7c;
}


/* =========================================
   TABLET / IPAD
========================================= */

@media (max-width: 900px) {

    .study-welcome {
        padding: 27px;
    }

    .welcome-illustration {
        margin-right: 5px;
    }

    .tool-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 650px) {

    .study-welcome {
        min-height: auto;
        padding: 25px;
    }

    .welcome-illustration {
        width: 115px;
        height: 115px;
        margin-right: 0;
    }

    .illustration-paper {
        transform: scale(.82) rotate(-6deg);
    }

    .materials-section {
        padding: 19px;
    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 520px) {

    .study-welcome {
        padding: 23px 20px;
    }

    .welcome-illustration {
        display: none;
    }

    .study-welcome h1 {
        font-size: 29px;
    }

    .welcome-actions {
        width: 100%;
    }

    .edit-button,
    .delete-button {
        flex: 1;
        padding: 0 10px;
        font-size: 10px;
    }

    .section-heading h2 {
        font-size: 20px;
    }

    .section-heading p {
        font-size: 10px;
    }

    .materials-count {
        width: 55px;
        height: 55px;
    }

    .material-item {
        gap: 10px;
        padding: 10px;
    }

    .material-file-icon {
        width: 37px;
        height: 37px;
    }

    .material-details strong {
        font-size: 10px;
    }

    .uploaded-badge {
        padding: 6px 8px;
        font-size: 8px;
    }

    .tool-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 13px;
    }

    .tool-card {
        min-height: 245px;
        padding: 19px;
    }

    .study-footer-note {
        gap: 7px;
        font-size: 10px;
    }

}


/* =========================================
   SMALL MOBILE
========================================= */

@media (max-width: 360px) {

    .uploaded-badge {
        display: none;
    }

    .modal-card {
        padding: 30px 19px;
    }

    .modal-actions {
        flex-direction: column;
    }

    .modal-actions form,
    .modal-actions button {
        width: 100%;
    }

}


/* =========================================
   REDUCED MOTION
========================================= */

@media (prefers-reduced-motion: reduce) {

    .study-page *,
    .study-page *::before,
    .study-page *::after {
        scroll-behavior: auto !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }

}

</style>


<script>

const deleteModal = document.getElementById('deleteModal');

function openDeleteModal() {
    if (!deleteModal) return;

    deleteModal.classList.add('open');
    deleteModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    const cancelButton = deleteModal.querySelector('.cancel-button');
    cancelButton?.focus();
}

function closeDeleteModal() {
    if (!deleteModal) return;

    deleteModal.classList.remove('open');
    deleteModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

deleteModal?.addEventListener('click', function (event) {
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
});

document.addEventListener('keydown', function (event) {
    if (
        event.key === 'Escape' &&
        deleteModal?.classList.contains('open')
    ) {
        closeDeleteModal();
    }
});

</script>

@endsection
