
@extends('layouts.app')

@section('title', $set->title)

@section('content')

<div class="study-page">

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- STUDY HEADING -->
    <section class="panel study-heading">

        <div class="study-heading-copy">
            <span class="page-kicker">♡ Your study collection</span>

            <h1 class="page-title">{{ $set->title }}</h1>

            <p class="page-description">
                Choose how you would like to study your material today.
            </p>
        </div>

        <div class="heading-actions">

            <a href="{{ route('sets.edit', $set) }}" class="button">
                ✎ Edit material
            </a>

            <button
                type="button"
                class="delete-button"
                onclick="openDeleteModal()"
            >
                <span aria-hidden="true">⌫</span>
                Delete
            </button>

        </div>

    </section>


    <!-- UPLOADED MATERIALS -->
    <section class="panel uploaded-materials-section">

        <div class="section-heading">

            <div>
                <span class="page-kicker">♡ Your study files</span>

                <h2>Uploaded Materials</h2>

                <p>
                    {{ $set->materials->count() }}
                    {{ Str::plural('file', $set->materials->count()) }}
                    in this study collection.
                </p>
            </div>

            <span class="section-decoration">୨୧</span>

        </div>

        <div class="uploaded-materials-list">

            @forelse ($set->materials as $material)

                <div class="uploaded-material-item">

                    <div class="uploaded-material-icon">
                        <span aria-hidden="true">▤</span>
                    </div>

                    <div class="uploaded-material-info">

                        <strong>{{ $material->original_name }}</strong>

                        <span>
                            {{ number_format($material->file_size / 1024, 1) }} KB
                        </span>

                    </div>

                    <span class="material-status">♡ Uploaded</span>

                </div>

            @empty

                <p class="empty-materials">
                    No uploaded materials yet.
                </p>

            @endforelse

        </div>

    </section>


    <!-- STUDY TOOLS -->
    <section class="study-tools-section">

        <div class="study-tools-heading">

            <div>
                <span class="page-kicker">♡ Your learning corner</span>

                <h2>Choose Your Study Tool</h2>

                <p>
                    Pick a way to review and practice your materials.
                </p>
            </div>

            <span class="section-decoration">୨୧</span>

        </div>


        <div class="tool-grid">

            <!-- SUMMARY -->
            <article class="panel tool-card summary-tool">

                <div class="tool-top">

                    <div class="tool-icon notes-icon">
                        <span aria-hidden="true">▤</span>
                    </div>

                    <span class="tool-number">01</span>

                </div>

                <span class="tool-label">READ & REVIEW</span>

                <h3>Study Summary</h3>

                <p>
                    Read organized notes and important points
                    generated from your uploaded material.
                </p>

                <a
                    href="{{ route('sets.summary.setup', $set) }}"
                    class="tool-link"
                >
                    Open summary <span>→</span>
                </a>

            </article>


            <!-- FLASHCARDS -->
            <article class="panel tool-card flashcard-tool">

                <div class="tool-top">

                    <div class="tool-icon flashcard-icon">
                        <span aria-hidden="true">▱</span>
                    </div>

                    <span class="tool-number">02</span>

                </div>

                <span class="tool-label">PRACTICE & REMEMBER</span>

                <h3>Flashcards</h3>

                <p>
                    Review key concepts with interactive
                    flashcards and test your memory.
                </p>

                <a
                    href="{{ route('sets.flashcards', $set) }}"
                    class="tool-link"
                >
                    Open flashcards <span>→</span>
                </a>

            </article>


            <!-- QUIZ -->
            <article class="panel tool-card quiz-tool">

                <div class="tool-top">

                    <div class="tool-icon quiz-icon">
                        <span aria-hidden="true">?</span>
                    </div>

                    <span class="tool-number">03</span>

                </div>

                <span class="tool-label">TEST YOUR KNOWLEDGE</span>

                <h3>Quiz Time</h3>

                <p>
                    Answer questions about your material
                    and see how much you have learned.
                </p>

                <a
                    href="{{ route('sets.quiz', $set) }}"
                    class="tool-link"
                >
                    Start quiz <span>→</span>
                </a>

            </article>

        </div>

    </section>


    <!-- BOTTOM NOTE -->
    <div class="study-bottom-note">
        <span>♡</span>
        One page at a time, one step closer to learning.
        <span>♡</span>
    </div>

</div>


<!-- DELETE CONFIRMATION MODAL -->
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

        <div class="modal-bow">୨୧</div>

        <span class="modal-icon">⌫</span>

        <span class="modal-kicker">A LITTLE CONFIRMATION</span>

        <h2 id="modal-title">Delete this study set?</h2>

        <p>
            Are you sure you want to delete
            <strong>{{ $set->title }}</strong>?
        </p>

        <p class="modal-note">
            Its uploaded materials, flashcards, and quiz questions
            will also be deleted.
        </p>

        <div class="modal-actions">

            <button
                type="button"
                class="cancel-button"
                onclick="closeDeleteModal()"
            >
                Keep it
            </button>

            <form
                method="POST"
                action="{{ route('sets.destroy', $set) }}"
            >

                @csrf
                @method('DELETE')

                <button type="submit" class="confirm-delete">
                    Yes, delete
                </button>

            </form>

        </div>

    </div>

</div>


<style>

/* =====================================
   PAGE BASE
===================================== */

.study-page {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.study-page *,
.study-page *::before,
.study-page *::after {
    box-sizing: border-box;
}


/* =====================================
   STUDY HEADING
===================================== */

.study-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 26px;
}

.study-heading-copy {
    min-width: 0;
}

.study-heading .page-title {
    overflow-wrap: anywhere;
}

.heading-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    flex-shrink: 0;
}

.delete-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 17px;
    border: 1px solid #f0cbd7;
    border-radius: 13px;
    background: #fff5f7;
    color: #a75c73;
    font: inherit;
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
    transition: .2s ease;
}

.delete-button:hover {
    background: #fce8ef;
    transform: translateY(-2px);
}


/* =====================================
   UPLOADED MATERIALS
===================================== */

.uploaded-materials-section {
    width: 100%;
    min-width: 0;
    margin-top: 18px;
    padding: 22px;
    border: 1px solid #f0dfe5;
    border-radius: 18px;
    background: #fffafb;
}

.uploaded-materials-section .section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 16px;
}

.uploaded-materials-section .section-heading h2 {
    margin: 7px 0 5px;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 21px;
}

.uploaded-materials-section .section-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
}

.section-decoration {
    color: #d99bb0;
    font-size: 28px;
    flex-shrink: 0;
}

.uploaded-materials-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.uploaded-material-item {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    min-width: 0;
    padding: 11px 14px;
    border: 1px solid #f0e1e7;
    border-radius: 12px;
    background: #fff7fa;
    transition: .2s ease;
}

.uploaded-material-item:hover {
    border-color: #e5bdcc;
    background: #fff1f6;
}

.uploaded-material-icon {
    display: grid;
    place-items: center;
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    border: 1px solid #efd5df;
    border-radius: 11px;
    background: #f9e8ef;
    color: #b87591;
}

.uploaded-material-icon span {
    font-family: Georgia, serif;
    font-size: 20px;
}

.uploaded-material-info {
    display: flex;
    flex: 1;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.uploaded-material-info strong {
    overflow: hidden;
    color: #755465;
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.uploaded-material-info span {
    color: #a18c96;
    font-size: 10px;
}

.material-status {
    flex-shrink: 0;
    padding: 6px 9px;
    border: 1px solid #e8d5e7;
    border-radius: 20px;
    background: #f7eff8;
    color: #927394;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.empty-materials {
    color: var(--muted);
    font-size: 12px;
}


/* =====================================
   STUDY TOOLS HEADING
===================================== */

.study-tools-section {
    width: 100%;
    min-width: 0;
    margin-top: 29px;
}

.study-tools-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 15px;
}

.study-tools-heading h2 {
    margin: 7px 0 5px;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 23px;
}

.study-tools-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
}


/* =====================================
   COMPACT STUDY TOOL GRID
===================================== */

.tool-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    align-items: stretch;
    gap: 12px;
    width: 100%;
    max-width: 100%;
    min-width: 0;
}

.tool-card {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    min-width: 0;
    min-height: 225px;
    padding: 16px;
    overflow: hidden;
    border: 1px solid #f0dce5;
    border-radius: 16px;
    background: #fffafb;
    box-shadow: 0 4px 14px rgba(133, 85, 105, .045);
    transition: transform .2s ease, box-shadow .2s ease;
}

.tool-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 20px rgba(133, 85, 105, .09);
}


/* CARD ICONS */

.tool-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 17px;
}

.tool-icon {
    display: grid;
    place-items: center;
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border: 1px solid #f1dce5;
    border-radius: 12px;
}

.tool-icon span {
    display: inline-block;
    font-family: Georgia, serif;
    font-size: 21px;
    font-weight: bold;
    line-height: 1;
}

.notes-icon {
    background: #fff0f5;
    color: #bd7895;
}

.flashcard-icon {
    background: #fdf0e9;
    color: #c28d78;
}

.quiz-icon {
    background: #f0effb;
    color: #9185c4;
}

.tool-number {
    color: #d7aebe;
    font-family: Georgia, serif;
    font-size: 13px;
}


/* CARD TEXT */

.tool-label {
    margin-bottom: 7px;
    color: #bd829a;
    font-size: 8px;
    font-weight: 900;
    letter-spacing: .8px;
    line-height: 1.5;
}

.tool-card h3 {
    margin: 0 0 8px;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 17px;
    line-height: 1.4;
}

.tool-card p {
    margin: 0 0 15px;
    color: #998591;
    font-size: 10px;
    line-height: 1.7;
}


/* CARD LINKS */

.tool-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 7px;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid #f0dfe6;
    color: #bd7895;
    font-size: 10px;
    font-weight: 900;
    text-decoration: none;
    transition: color .2s ease;
}

.tool-link span {
    font-size: 16px;
    transition: transform .2s ease;
}

.tool-link:hover {
    color: #92566f;
}

.tool-link:hover span {
    transform: translateX(4px);
}


/* =====================================
   BOTTOM NOTE
===================================== */

.study-bottom-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 23px 0 10px;
    color: #a17e8e;
    font-family: Georgia, serif;
    font-size: 12px;
    font-style: italic;
    text-align: center;
}

.study-bottom-note span {
    color: #d99bb0;
}


/* =====================================
   SUCCESS AND ERROR MESSAGES
===================================== */

.success-message {
    padding: 13px 16px;
    border: 1px solid #cce5d5;
    border-radius: 13px;
    margin-bottom: 18px;
    background: #f1faf4;
    color: #527e61;
}

.error-message {
    padding: 13px 16px;
    border: 1px solid #efc2ce;
    border-radius: 13px;
    margin-bottom: 18px;
    background: #fff0f3;
    color: #a34e65;
}


/* =====================================
   DELETE MODAL
===================================== */

.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: #49313d75;
    backdrop-filter: blur(5px);
}

.modal-overlay.open {
    display: flex;
}

.modal-card {
    position: relative;
    width: min(100%, 410px);
    padding: 34px 27px;
    border: 1px solid #f2dce5;
    border-radius: 25px;
    background: #fffdfb;
    box-shadow: 0 20px 70px #49313d30;
    text-align: center;
}

.modal-bow {
    position: absolute;
    top: 15px;
    right: 20px;
    color: #e4a5bf;
    font-size: 30px;
}

.modal-icon {
    display: grid;
    place-items: center;
    width: 65px;
    height: 65px;
    border-radius: 50%;
    margin: 0 auto 13px;
    background: #fff0f5;
    color: #c47e9b;
    font-family: Georgia, serif;
    font-size: 28px;
}

.modal-kicker {
    color: #bd8ba0;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .15em;
}

.modal-card h2 {
    margin: 10px 0;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 23px;
}

.modal-card p {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.7;
}

.modal-note {
    font-size: 12px !important;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 25px;
}

.cancel-button,
.confirm-delete {
    min-height: 44px;
    padding: 0 17px;
    border-radius: 13px;
    font: inherit;
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
}

.cancel-button {
    border: 1px solid var(--line);
    background: white;
    color: var(--pink-dark);
}

.confirm-delete {
    border: 0;
    background: #c97591;
    color: white;
}

.confirm-delete:hover {
    background: #ad5d7a;
}


/* =====================================
   TABLET
===================================== */

@media (max-width: 760px) {

    .tool-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}


/* =====================================
   MOBILE
===================================== */

@media (max-width: 520px) {

    .study-heading {
        align-items: flex-start;
        flex-direction: column;
        padding: 22px;
    }

    .heading-actions {
        width: 100%;
    }

    .heading-actions .button,
    .heading-actions .delete-button {
        flex: 1;
    }

    .tool-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 13px;
    }

    .tool-card {
        min-height: 215px;
        padding: 19px;
    }

    .uploaded-materials-section {
        padding: 17px;
    }

    .uploaded-material-item {
        gap: 10px;
        padding: 11px;
    }

    .uploaded-material-icon {
        width: 36px;
        height: 36px;
    }

    .material-status {
        padding: 5px 7px;
        font-size: 9px;
    }

    .study-tools-heading h2 {
        font-size: 21px;
    }

}


/* =====================================
   VERY SMALL SCREENS
===================================== */

@media (max-width: 380px) {

    .uploaded-material-item {
        flex-wrap: wrap;
    }

    .material-status {
        margin-left: 46px;
    }

    .study-bottom-note {
        font-size: 10px;
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
