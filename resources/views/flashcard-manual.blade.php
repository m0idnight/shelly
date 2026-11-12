
@extends('layouts.app')

@section('title', 'Manual Flashcards')

@push('styles')
<style>
    .manual-page {
        width: 100%;
        max-width: 920px;
        margin: 0 auto;
    }

    /* TOP ACTION BUTTON */

    .manual-top-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .study-flashcards-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 46px;
        padding: 12px 21px;
        border: 1px solid var(--pink);
        border-radius: 13px;
        background: linear-gradient(135deg, #e7a1b9, #d88da8);
        color: white;
        font-family: "Nunito", sans-serif;
        font-size: 12px;
        font-weight: 900;
        text-decoration: none;
        box-shadow: 0 6px 16px rgba(166, 95, 123, .14);
        transition: .22s ease;
    }

    .study-flashcards-button:hover {
        border-color: var(--pink-dark);
        background: linear-gradient(135deg, #d88da8, #c97f9b);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 9px 20px rgba(166, 95, 123, .2);
    }

    .study-flashcards-button .button-arrow {
        font-size: 16px;
        transition: transform .2s ease;
    }

    .study-flashcards-button:hover .button-arrow {
        transform: translateX(3px);
    }

    .manual-panel {
        padding: 30px;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: rgba(255,255,255,.82);
        box-shadow: var(--shadow);
    }

    .manual-section-heading {
        margin-bottom: 22px;
        text-align: center;
    }

    .manual-section-heading h2 {
        margin: 8px 0;
        color: #694c5b;
        font-family: "Playfair Display", serif;
        font-size: 25px;
        font-weight: 600;
    }

    .manual-section-heading p {
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.8;
    }

    .manual-eyebrow {
        color: var(--pink-dark);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .15em;
        text-transform: uppercase;
    }

    .manual-form,
    .manual-card-item {
        padding: 22px;
        margin-bottom: 16px;
        border: 1px solid var(--line);
        border-radius: 15px;
        background: #fffafb;
    }

    .manual-form label,
    .manual-card-item label {
        display: block;
        margin: 13px 0 7px;
        color: var(--pink-dark);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .5px;
    }

    .manual-form textarea,
    .manual-card-item textarea {
        display: block;
        width: 100%;
        min-height: 75px;
        padding: 13px;
        border: 1px solid var(--line);
        border-radius: 10px;
        outline: none;
        background: white;
        color: var(--ink);
        font-family: "Nunito", sans-serif;
        font-size: 14px;
        line-height: 1.6;
        resize: vertical;
        transition: .2s;
    }

    .manual-form textarea:focus,
    .manual-card-item textarea:focus {
        border-color: var(--pink);
        box-shadow: 0 0 0 3px rgba(231,161,185,.15);
    }

    .manual-submit,
    .manual-edit-button,
    .manual-delete-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 43px;
        padding: 11px 18px;
        border: 1px solid var(--pink);
        border-radius: 11px;
        background: var(--pink);
        color: white;
        font-family: "Nunito", sans-serif;
        font-size: 12px;
        font-weight: 900;
        transition: .2s ease;
        cursor: pointer;
    }

    .manual-submit {
        margin-top: 16px;
    }

    .manual-submit:hover,
    .manual-edit-button:hover {
        border-color: var(--pink-dark);
        background: var(--pink-dark);
    }

    .manual-list {
        margin-top: 38px;
        padding-top: 30px;
        border-top: 1px solid var(--line);
    }

    .manual-card-item .card-number {
        display: block;
        margin-bottom: 17px;
        color: var(--pink-dark);
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 1px;
    }

    .manual-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }

    .manual-actions button {
        margin: 0;
    }

    .manual-delete-button {
        border-color: #e5b7c2;
        background: white;
        color: var(--pink-dark);
    }

    .manual-delete-button:hover {
        border-color: var(--pink);
        background: var(--pink-soft);
    }

    .manual-empty {
        padding: 25px 20px;
        border: 1px dashed var(--line);
        border-radius: 13px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.8;
        text-align: center;
        background: #fffafb;
    }

    .manual-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--pink-soft);
        color: var(--pink-dark);
        font-size: 13px;
        line-height: 1.7;
    }

    .manual-alert.success {
        border-color: #d5e8d9;
        background: #f4faf5;
        color: #64836a;
    }

    /* DELETE CONFIRMATION MODAL */

    .dashboard-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(65, 43, 55, .42);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        opacity: 0;
        visibility: hidden;
        transition: .25s ease;
    }

    .dashboard-modal-overlay.open {
        opacity: 1;
        visibility: visible;
    }

    .dashboard-modal {
        position: relative;
        width: 100%;
        max-width: 410px;
        padding: 38px 32px 30px;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: #fffafb;
        text-align: center;
        box-shadow: 0 20px 65px rgba(65, 43, 55, .18);
        transform: translateY(12px);
        transition: .25s ease;
    }

    .dashboard-modal-overlay.open .dashboard-modal {
        transform: translateY(0);
    }

    .dashboard-modal-bow {
        display: block;
        margin-bottom: 14px;
        color: var(--pink-dark);
        font-size: 25px;
    }

    .dashboard-modal-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: var(--pink-soft);
        color: var(--pink-dark);
        font-size: 20px;
    }

    .dashboard-modal-kicker {
        display: block;
        margin-bottom: 9px;
        color: var(--pink-dark);
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 1.8px;
    }

    .dashboard-modal h2 {
        margin: 0 0 13px;
        color: var(--ink);
        font-family: "Playfair Display", serif;
        font-size: 24px;
        font-weight: 600;
    }

    .dashboard-modal p {
        margin: 0 auto 12px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .dashboard-modal p strong {
        color: var(--ink);
    }

    .dashboard-modal .dashboard-modal-note {
        margin-top: 14px;
        font-size: 12px;
    }

    .dashboard-modal-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 27px;
    }

    .dashboard-modal-actions form {
        margin: 0;
    }

    .dashboard-cancel,
    .dashboard-confirm-delete {
        min-width: 110px;
        min-height: 42px;
        padding: 11px 17px;
        border-radius: 10px;
        font-family: "Nunito", sans-serif;
        font-size: 12px;
        font-weight: 900;
        cursor: pointer;
        transition: .2s ease;
    }

    .dashboard-cancel {
        border: 1px solid var(--line);
        background: white;
        color: var(--ink);
    }

    .dashboard-cancel:hover {
        border-color: var(--pink);
        background: var(--pink-soft);
    }

    .dashboard-confirm-delete {
        border: 1px solid var(--pink);
        background: var(--pink);
        color: white;
    }

    .dashboard-confirm-delete:hover {
        border-color: var(--pink-dark);
        background: var(--pink-dark);
    }

    @media (max-width: 700px) {
        .manual-panel {
            padding: 22px 17px;
        }

        .manual-form,
        .manual-card-item {
            padding: 18px;
        }
    }

    @media (max-width: 480px) {
        .manual-top-actions {
            justify-content: stretch;
        }

        .study-flashcards-button {
            width: 100%;
        }

        .manual-actions {
            gap: 9px;
        }

        .manual-actions button {
            flex: 1;
        }

        .dashboard-modal {
            padding: 32px 22px 25px;
        }

        .dashboard-modal-actions {
            gap: 9px;
        }

        .dashboard-cancel,
        .dashboard-confirm-delete {
            min-width: 0;
            flex: 1;
        }
    }
</style>
@endpush

@section('content')

<div class="manual-page">

    {{-- STUDY FLASHCARDS BUTTON --}}

    <div class="manual-top-actions">
        <a
            href="{{ route('sets.flashcards', $set) }}"
            class="study-flashcards-button"
        >
            <span>Study Flashcards</span>
            <span class="button-arrow">→</span>
        </a>
    </div>

    <main class="manual-panel">

        {{-- VALIDATION AND SUCCESS MESSAGES --}}

        @if ($errors->any())
            <div class="manual-alert">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="manual-alert success">
                {{ session('success') }}
            </div>
        @endif

        {{-- CREATE NEW MANUAL FLASHCARD --}}

        <section class="create-section">

            <div class="manual-section-heading">
                <span class="manual-eyebrow">CREATE SOMETHING YOURSELF</span>

                <h2>Add a New Card</h2>

                <p>
                    Write your own questions, terms, and answers
                    to add to your study collection.
                </p>
            </div>

            <form
                class="manual-form"
                method="POST"
                action="{{ route('sets.flashcards.store', $set) }}"
            >
                @csrf

                <label>QUESTION / TERM</label>

                <textarea
                    name="front"
                    rows="2"
                    maxlength="5000"
                    placeholder="Write your question or term..."
                    required
                >{{ old('front') }}</textarea>

                <label>ANSWER / DEFINITION</label>

                <textarea
                    name="back"
                    rows="3"
                    maxlength="5000"
                    placeholder="Write the answer or explanation..."
                    required
                >{{ old('back') }}</textarea>

                <button type="submit" class="manual-submit">
                    + Add Flashcard
                </button>
            </form>

        </section>

        {{-- PERSONAL FLASHCARD COLLECTION --}}

        <section class="manual-list">

            <div class="manual-section-heading">
                <span class="manual-eyebrow">YOUR PERSONAL COLLECTION</span>

                <h2>Your Manual Cards</h2>

                <p>Edit or remove the cards you've created.</p>
            </div>

            @forelse ($manualCards as $manualCard)

                <article class="manual-card-item">

                    <span class="card-number">
                        PERSONAL CARD #{{ $loop->iteration }}
                    </span>

                    <form
                        method="POST"
                        action="{{ route('sets.flashcards.update', [$set, $manualCard]) }}"
                    >
                        @csrf
                        @method('PUT')

                        <label>QUESTION / TERM</label>

                        <textarea
                            name="front"
                            rows="2"
                            maxlength="5000"
                            required
                        >{{ $manualCard->front }}</textarea>

                        <label>ANSWER / DEFINITION</label>

                        <textarea
                            name="back"
                            rows="3"
                            maxlength="5000"
                            required
                        >{{ $manualCard->back }}</textarea>

                        <div class="manual-actions">

                            <button
                                type="submit"
                                class="manual-edit-button"
                            >
                                Save Changes
                            </button>

                            <button
                                type="button"
                                class="manual-delete-button"
                                data-action="{{ route('sets.flashcards.destroy', [$set, $manualCard]) }}"
                                data-title="{{ $manualCard->front }}"
                                onclick="openManualDeleteModal(
                                    this.dataset.action,
                                    this.dataset.title
                                )"
                            >
                                Delete Card
                            </button>

                        </div>

                    </form>

                </article>

            @empty

                <div class="manual-empty">
                    You haven't created any personal flashcards yet.
                    Your first one can be added using the form above.
                </div>

            @endforelse

        </section>

    </main>

</div>

{{-- DELETE CONFIRMATION MODAL --}}

<div
    class="dashboard-modal-overlay"
    id="manualDeleteModal"
    aria-hidden="true"
>
    <div
        class="dashboard-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="manual-delete-title"
    >

        <span class="dashboard-modal-bow">୨୧</span>

        <div class="dashboard-modal-icon">
            ✦
        </div>

        <span class="dashboard-modal-kicker">
            A LITTLE CONFIRMATION
        </span>

        <h2 id="manual-delete-title">
            Delete this flashcard?
        </h2>

        <p>
            Are you sure you want to delete
            <strong id="manualDeleteCardName"></strong>?
        </p>

        <p class="dashboard-modal-note">
            This personal flashcard will be permanently deleted.
        </p>

        <div class="dashboard-modal-actions">

            <button
                type="button"
                class="dashboard-cancel"
                onclick="closeManualDeleteModal()"
            >
                Keep it
            </button>

            <form
                id="manualDeleteForm"
                method="POST"
                action=""
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="dashboard-confirm-delete"
                >
                    Yes, delete
                </button>
            </form>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const manualDeleteModal =
        document.getElementById('manualDeleteModal');

    const manualDeleteForm =
        document.getElementById('manualDeleteForm');

    const manualDeleteCardName =
        document.getElementById('manualDeleteCardName');

    function openManualDeleteModal(action, title) {
        manualDeleteForm.action = action;

        manualDeleteCardName.textContent = title;

        manualDeleteModal.classList.add('open');
        manualDeleteModal.setAttribute('aria-hidden', 'false');

        document.body.style.overflow = 'hidden';
    }

    function closeManualDeleteModal() {
        manualDeleteModal.classList.remove('open');
        manualDeleteModal.setAttribute('aria-hidden', 'true');

        document.body.style.overflow = '';
    }

    // Close when clicking outside the modal.

    manualDeleteModal.addEventListener('click', function(event) {
        if (event.target === manualDeleteModal) {
            closeManualDeleteModal();
        }
    });

    // Close when pressing Escape.

    document.addEventListener('keydown', function(event) {
        if (
            event.key === 'Escape' &&
            manualDeleteModal.classList.contains('open')
        ) {
            closeManualDeleteModal();
        }
    });
</script>
@endpush
