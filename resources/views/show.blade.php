
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

    <!-- Study heading -->
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
                <i class="fa-regular fa-trash-can"></i>
                Delete
            </button>

        </div>

    </section>

    <!-- Study tools -->
    <section class="study-tools-section">

        <div class="section-heading">
            <div>
                <span class="page-kicker">✧ Your study tools</span>
                <h2>What shall we study?</h2>
                <p>Pick a little activity to help you learn.</p>
            </div>

            <span class="section-decoration">୨୧</span>
        </div>

        <div class="tool-grid">

            <!-- Summary -->
            <article class="panel tool-card summary-tool">

                <div class="tool-top">
                    <div class="tool-icon notes-icon">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>

                    <span class="tool-number">01</span>
                </div>

                <span class="tool-label">READ & REVIEW</span>

                <h3>Study Summary</h3>

                <p>
                    Read organized notes and important points
                    generated from your uploaded material.
                </p>

                <a href="{{ route('sets.summary.setup', $set) }}"
                   class="tool-link">
                    Open summary <span>→</span>
                </a>

            </article>

            <!-- Flashcards -->
            <article class="panel tool-card flashcard-tool">

                <div class="tool-top">
                    <div class="tool-icon flashcard-icon">
                        <i class="fa-regular fa-clone"></i>
                    </div>

                    <span class="tool-number">02</span>
                </div>

                <span class="tool-label">PRACTICE & REMEMBER</span>

                <h3>Flashcards</h3>

                <p>
                    Review key concepts with interactive
                    flashcards and test your memory.
                </p>

                <a href="{{ route('sets.flashcards', $set) }}"
                   class="tool-link">
                    Open flashcards <span>→</span>
                </a>

            </article>

            <!-- Quiz -->
            <article class="panel tool-card quiz-tool">

                <div class="tool-top">
                    <div class="tool-icon quiz-icon">
                        <i class="fa-regular fa-circle-question"></i>
                    </div>

                    <span class="tool-number">03</span>
                </div>

                <span class="tool-label">TEST YOUR KNOWLEDGE</span>

                <h3>Quiz Time</h3>

                <p>
                    Answer questions about your material
                    and see how much you have learned.
                </p>

                <a href="{{ route('sets.quiz', $set) }}"
                   class="tool-link">
                    Start quiz <span>→</span>
                </a>

            </article>

        </div>

    </section>

    <div class="study-bottom-note">
        <span>♡</span>
        One page at a time, one step closer to learning.
        <span>♡</span>
    </div>

</div>


<!-- Delete confirmation modal -->
<div class="modal-overlay"
     id="deleteModal"
     aria-hidden="true">

    <div class="modal-card"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-title">

        <div class="modal-bow">୨୧</div>

        <span class="modal-icon">
            <i class="fa-regular fa-trash-can"></i>
        </span>

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
                onclick="closeDeleteModal()">
                Keep it
            </button>

            <form method="POST"
                  action="{{ route('sets.destroy', $set) }}">

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

/* Study heading */

.study-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
    padding: 30px;
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
    gap: 10px;
    flex-shrink: 0;
}

.delete-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 48px;
    padding: 0 20px;
    border: 1px solid #f0cbd7;
    border-radius: 15px;
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


/* Study tools section */

.study-tools-section {
    margin-top: 35px;
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 17px;
}

.section-heading h2 {
    margin: 7px 0 5px;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 24px;
}

.section-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
}

.section-decoration {
    color: #d99bb0;
    font-size: 30px;
}


/* Tool cards */

.tool-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px;
}

.tool-card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 245px;
    padding: 23px;
    overflow: hidden;
    transition: .2s ease;
}

.tool-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(133, 85, 105, .09);
}

.tool-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.tool-icon {
    display: grid;
    place-items: center;
    width: 46px;
    height: 46px;
    border-radius: 15px;
    font-size: 20px;
}

.notes-icon {
    background: #fff0f5;
    color: #c47e9b;
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
    color: #d9c4cd;
    font-family: Georgia, serif;
    font-size: 15px;
}

.tool-label {
    color: #b98a9d;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .13em;
}

.tool-card h3 {
    margin: 8px 0;
    color: #795568;
    font-family: Georgia, serif;
    font-size: 19px;
}

.tool-card p {
    margin: 0 0 20px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.tool-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-top: 13px;
    border-top: 1px solid #f0e2e7;
    margin-top: auto;
    color: #b16f8a;
    font-size: 11px;
    font-weight: 900;
    text-decoration: none;
    transition: .2s ease;
}

.tool-link span {
    font-size: 17px;
    transition: transform .2s ease;
}

.tool-link:hover {
    color: #92516c;
}

.tool-link:hover span {
    transform: translateX(4px);
}


/* Bottom note */

.study-bottom-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 25px 0 10px;
    color: #a17e8e;
    font-family: Georgia, serif;
    font-size: 12px;
    font-style: italic;
    text-align: center;
}

.study-bottom-note span {
    color: #d99bb0;
}


/* Messages */

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


/* Delete modal */

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
    font-size: 25px;
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


/* Responsive */

@media (max-width: 850px) {
    .tool-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px) {
    .study-heading {
        align-items: flex-start;
        flex-direction: column;
        padding: 23px;
    }

    .heading-actions {
        width: 100%;
    }

    .heading-actions .button,
    .heading-actions .delete-button {
        flex: 1;
    }

    .tool-grid {
        grid-template-columns: 1fr;
    }

    .tool-card {
        min-height: 220px;
    }

    .section-heading h2 {
        font-size: 21px;
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
