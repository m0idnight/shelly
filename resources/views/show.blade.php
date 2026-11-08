@extends('layouts.app')

@section('title', $set->title)

@section('content')
<div class="study-page">

    <a href="{{ route('dashboard') }}" class="back-link">
        ← Back to dashboard
    </a>

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

    <section class="panel study-heading">
        <div>
            <span class="page-kicker">♡ Your study collection</span>
            <h1 class="page-title">{{ $set->title }}</h1>
            <p class="page-description">
                Your notes, summaries, and study activities all in one place.
            </p>
        </div>

        <div class="heading-actions">
            <a href="{{ route('sets.edit', $set) }}" class="button">
                ✎ Edit
            </a>

            <button
                type="button"
                class="delete-button"
                onclick="openDeleteModal()"
            >
                Delete
            </button>
        </div>
    </section>

    <section class="tool-grid">
        <article class="panel tool-card">
            <span class="tool-icon">✧</span>
            <h2>Study summary</h2>
            <p>Turn your material into clear, organized notes.</p>

            <form method="POST" action="{{ route('sets.summarize', $set) }}">
                @csrf
                <button class="button" type="submit">
                    {{ $set->summary ? 'Regenerate summary' : 'Make summary' }} ✨
                </button>
            </form>
        </article>

        <article class="panel tool-card">
            <span class="tool-icon">🃏</span>
            <h2>Flashcards</h2>
            <p>Review important ideas using interactive cards.</p>

            <a href="{{ route('sets.flashcards', $set) }}" class="button">
                Open flashcards →
            </a>
        </article>

        <article class="panel tool-card">
            <span class="tool-icon">🎀</span>
            <h2>Quiz time</h2>
            <p>Test what you've learned with a fun quiz.</p>

            <a href="{{ route('sets.quiz', $set) }}" class="button">
                Open quiz →
            </a>
        </article>
    </section>

    @if ($set->summary)
        <section class="panel content-card">
            <span class="page-kicker">✧ Your generated notes</span>
            <h2>Study summary</h2>

            <div class="markdown-content">
                {!! Str::markdown($set->summary, ['html_input' => 'escape']) !!}
            </div>
        </section>
    @endif

    <section class="panel content-card">
        <span class="page-kicker">📖 Original material</span>
        <h2>Your uploaded file</h2>

        @if ($set->image_path)
            <img
                src="{{ asset('storage/' . $set->image_path) }}"
                alt="Uploaded study material"
                class="original-image"
            >
        @elseif ($set->source_text)
            <pre class="original-text">{{ $set->source_text }}</pre>
        @else
            <p class="empty-note">No original material is available.</p>
        @endif
    </section>
</div>

<!-- Delete confirmation modal -->
<div class="modal-overlay" id="deleteModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true"
         aria-labelledby="modal-title">

        <div class="modal-bow">୨୧</div>
        <span class="modal-icon">🧸</span>

        <h2 id="modal-title">Delete this study set?</h2>

        <p>
            Are you sure you want to delete
            <strong>{{ $set->title }}</strong>?
        </p>

        <p class="modal-note">
            Its flashcards and quiz questions will also be deleted.
            Your study history will be preserved.
        </p>

        <div class="modal-actions">
            <button type="button" class="cancel-button"
                    onclick="closeDeleteModal()">
                Keep it
            </button>

            <form method="POST" action="{{ route('sets.destroy', $set) }}">
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
    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: var(--pink-dark);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    .success-message {
        margin-bottom: 18px;
        padding: 13px 16px;
        border: 1px solid #cce5d5;
        border-radius: 13px;
        background: #f1faf4;
        color: #527e61;
    }

    .study-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        padding: 30px;
    }

    .study-heading .page-title { overflow-wrap: anywhere; }

    .heading-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .delete-button {
        min-height: 48px;
        padding: 0 20px;
        border: 1px solid #f0cbd7;
        border-radius: 15px;
        background: #fff5f7;
        color: #a75c73;
        font: inherit;
        font-weight: 900;
        cursor: pointer;
    }

    .tool-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 17px;
        margin: 22px 0;
    }

    .tool-card { padding: 24px; }

    .tool-icon {
        display: grid;
        place-items: center;
        width: 43px;
        height: 43px;
        border-radius: 15px;
        background: #ffedf4;
        font-size: 22px;
    }

    .tool-card h2, .content-card h2 {
        margin: 15px 0 8px;
        color: #795568;
        font-size: 19px;
    }

    .tool-card p {
        min-height: 43px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .tool-card .button {
        width: 100%;
        margin-top: 10px;
        padding: 0 12px;
        font-size: 12px;
    }

    .content-card {
        margin-top: 20px;
        padding: 28px;
    }

    .markdown-content {
        color: var(--ink);
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .markdown-content h1,
    .markdown-content h2,
    .markdown-content h3 {
        color: #795568;
    }

    .original-image {
        display: block;
        width: 100%;
        max-height: 650px;
        object-fit: contain;
        border-radius: 15px;
        background: #fff7fa;
    }

    .original-text {
        max-height: 500px;
        overflow: auto;
        padding: 20px;
        border: 1px solid var(--line);
        border-radius: 15px;
        background: #fff8fb;
        color: var(--ink);
        font: inherit;
        font-size: 13px;
        line-height: 1.8;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .empty-note { color: var(--muted); }

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

    .modal-overlay.open { display: flex; }

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

    .modal-icon { font-size: 40px; }

    .modal-card h2 {
        margin: 14px 0 8px;
        color: #795568;
    }

    .modal-card p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .modal-note { font-size: 12px !important; }

    .modal-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
    }

    .cancel-button, .confirm-delete {
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

    @media (max-width: 760px) {
        .study-heading {
            align-items: flex-start;
            flex-direction: column;
            padding: 23px;
        }

        .tool-grid { grid-template-columns: 1fr; }
        .tool-card p { min-height: 0; }
        .content-card { padding: 22px; }
    }
</style>

<script>
    const deleteModal = document.getElementById('deleteModal');

    function openDeleteModal() {
        deleteModal.classList.add('open');
        deleteModal.setAttribute('aria-hidden', 'false');
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('open');
        deleteModal.setAttribute('aria-hidden', 'true');
    }

    deleteModal?.addEventListener('click', function (event) {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection
