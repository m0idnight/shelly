
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Manual Flashcards | Shelly</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #d987a5;
            --pink-dark: #b96f8e;
            --pink-light: #f9e6ed;
            --pink-pale: #fff5f7;
            --cream: #fffaf8;
            --ink: #503b3b;
            --muted: #a58c91;
            --line: #efd9df;
            --white: #fffdfd;
            --shadow: 0 12px 35px rgba(151, 95, 115, .07);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 34px 20px 55px;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            background:
                radial-gradient(circle at 5% 5%, #fcecf0, transparent 27%),
                radial-gradient(circle at 95% 90%, #fff0eb, transparent 25%),
                var(--cream);
        }

        button, a {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            cursor: pointer;
        }

        .page {
            width: 100%;
            max-width: 920px;
            margin: auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 52px;
        }

        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--ink);
        }

        .brand span {
            color: var(--pink);
        }

        .back-link {
            padding: 11px 17px;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: rgba(255,255,255,.65);
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .back-link:hover {
            background: var(--pink-light);
        }

        .heading {
            margin-bottom: 34px;
            text-align: center;
        }

        .eyebrow {
            color: var(--pink-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2.5px;
        }

        h1 {
            margin: 12px 0 8px;
            font-family: 'Playfair Display', serif;
            font-size: clamp(32px, 5vw, 43px);
            font-weight: 700;
            letter-spacing: .5px;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
            font-family: 'Playfair Display', serif;
            font-size: 15px;
        }

        .ornament {
            margin-top: 17px;
            color: var(--pink);
            font-size: 17px;
            letter-spacing: 10px;
        }

        .manual-panel {
            padding: 30px;
            border: 1px solid var(--line);
            border-radius: 7px;
            background: rgba(255,255,255,.76);
            box-shadow: var(--shadow);
        }

        .section-heading {
            margin-bottom: 22px;
            text-align: center;
        }

        .section-heading h2 {
            margin: 8px 0;
            font-family: 'Playfair Display', serif;
            font-size: 25px;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .manual-form,
        .manual-card-item {
            padding: 22px;
            margin-bottom: 16px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fffafb;
        }

        .manual-form label,
        .manual-card-item label {
            display: block;
            margin: 13px 0 7px;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .manual-form textarea,
        .manual-card-item textarea {
            display: block;
            width: 100%;
            min-height: 75px;
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 4px;
            outline: none;
            background: white;
            color: var(--ink);
            font: inherit;
            font-size: 13px;
            resize: vertical;
            transition: .2s;
        }

        .manual-form textarea:focus,
        .manual-card-item textarea:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(217,135,165,.10);
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
            border-radius: 4px;
            background: var(--pink);
            color: white;
            font-size: 11px;
            font-weight: 800;
            transition: .2s ease;
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
            font-weight: 800;
            letter-spacing: 1px;
        }

        /* EDIT AND DELETE BUTTONS */

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
            background: var(--pink-pale);
        }

        .manual-empty {
            padding: 25px 20px;
            border: 1px dashed var(--line);
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
            text-align: center;
        }

        .alert {
            padding: 13px 16px;
            margin-bottom: 18px;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: var(--pink-pale);
            color: var(--pink-dark);
            font-size: 12px;
            line-height: 1.6;
        }

        .alert.success {
            border-color: #d5e8d9;
            background: #f4faf5;
            color: #64836a;
        }

        .footer {
            margin-top: 35px;
            color: var(--muted);
            font-size: 11px;
            letter-spacing: 1px;
            text-align: center;
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
            border-radius: 12px;
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
            background: var(--pink-pale);
            color: var(--pink-dark);
            font-size: 20px;
        }

        .dashboard-modal-kicker {
            display: block;
            margin-bottom: 9px;
            color: var(--pink-dark);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.8px;
        }

        .dashboard-modal h2 {
            margin: 0 0 13px;
            color: var(--ink);
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
        }

        .dashboard-modal p {
            margin: 0 auto 12px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .dashboard-modal p strong {
            color: var(--ink);
        }

        .dashboard-modal .dashboard-modal-note {
            margin-top: 14px;
            font-size: 11px;
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
            border-radius: 4px;
            font-size: 11px;
            font-weight: 800;
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
            background: var(--pink-pale);
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

        @media (max-width: 600px) {
            body {
                padding: 22px 14px 40px;
            }

            .topbar {
                margin-bottom: 38px;
            }

            .manual-panel {
                padding: 20px 15px;
            }

            .manual-form,
            .manual-card-item {
                padding: 17px;
            }

            .back-link {
                padding: 9px 11px;
                font-size: 11px;
            }

            .manual-actions {
                gap: 10px;
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
</head>

<body>

<div class="page">

    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="brand">
            shelly<span>୨୧</span>
        </a>

        <a href="{{ route('sets.flashcards', $set) }}" class="back-link">
            ← Back to collection
        </a>
    </header>

    <section class="heading">
        <span class="eyebrow">A LITTLE PERSONAL TOUCH</span>

        <h1>Manual Flashcards</h1>

        <p class="subtitle">{{ $set->title }}</p>

        <div class="ornament">✿ ୨୧ ✿</div>
    </section>

    <main class="manual-panel">

        {{-- VALIDATION AND SUCCESS MESSAGES --}}

        @if ($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        {{-- CREATE NEW MANUAL FLASHCARD --}}

        <section class="create-section">

            <div class="section-heading">
                <span class="eyebrow">CREATE SOMETHING YOURSELF</span>

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

            <div class="section-heading">
                <span class="eyebrow">YOUR PERSONAL COLLECTION</span>

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

                        {{-- BOTH BUTTONS SHARE THE SAME ROW --}}

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

    <footer class="footer">
        made with love by shelly
    </footer>

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

</body>
</html>
