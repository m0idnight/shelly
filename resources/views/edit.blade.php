
@extends('layouts.app')

@section('title', 'Edit Study Set')

@section('content')
<div class="edit-page">
    <a href="{{ route('sets.show', $set) }}" class="back-link">
        ← Back to study set
    </a>

    <div class="edit-heading">
        <span class="page-kicker">🎀 A little update</span>
        <h1 class="page-title">Edit your study set</h1>
        <p class="page-description">
            Change the title or replace your study material.
            Your learning space stays yours. ♡
        </p>
    </div>

    <section class="panel edit-card">
        <div class="bow-decoration">୨୧</div>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('sets.update', $set) }}"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            <label for="title">Study set title</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $set->title) }}"
                required
                maxlength="255"
            >

            <label for="file">Replace study material <span>(optional)</span></label>

            <div class="file-area">
                <input
                    type="file"
                    id="file"
                    name="file"
                    accept=".pdf,.docx,.txt,.jpg,.jpeg,.png,.webp"
                >

                <p id="file-name">
                    Choose a new PDF, DOCX, TXT, or image file.
                </p>
            </div>

            <p class="note">
                Leave this empty if you only want to change the title.
                Replacing the material will reset its generated study content.
            </p>

            <div class="actions">
                <a href="{{ route('sets.show', $set) }}" class="cancel-button">
                    Cancel
                </a>

                <button type="submit" class="button">
                    Save changes ♡
                </button>
            </div>
        </form>
    </section>
</div>

<style>
    .edit-page { max-width: 680px; margin: auto; }

    .back-link {
        display: inline-block;
        margin-bottom: 25px;
        color: var(--pink-dark);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    .edit-heading { margin-bottom: 25px; }

    .edit-card {
        position: relative;
        padding: 35px;
        overflow: hidden;
    }

    .bow-decoration {
        position: absolute;
        top: 20px;
        right: 25px;
        color: #e4a5bf;
        font-size: 34px;
    }

    .edit-card label {
        display: block;
        margin: 22px 0 9px;
        color: #795568;
        font-size: 13px;
        font-weight: 900;
    }

    .edit-card label span {
        color: #b69ba8;
        font-weight: 600;
    }

    .edit-card input[type="text"] {
        width: 100%;
        min-height: 48px;
        padding: 12px 15px;
        border: 1px solid var(--line);
        border-radius: 13px;
        background: #fff8fb;
        color: var(--ink);
        font: inherit;
        outline: none;
    }

    .edit-card input:focus {
        border-color: var(--pink);
        box-shadow: 0 0 0 3px #d982a61a;
    }

    .file-area {
        padding: 20px;
        border: 1.5px dashed #e7b6c9;
        border-radius: 17px;
        background: #fff7fa;
    }

    .file-area input { max-width: 100%; }

    .file-area p {
        margin: 10px 0 0;
        color: var(--muted);
        font-size: 12px;
    }

    .note {
        margin: 14px 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .cancel-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 0 20px;
        border: 1px solid var(--line);
        border-radius: 15px;
        color: var(--pink-dark);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    @media (max-width: 600px) {
        .edit-card { padding: 25px 20px; }
        .actions { flex-direction: column-reverse; }
        .actions > * { width: 100%; }
    }
</style>

<script>
    document.getElementById('file')?.addEventListener('change', function () {
        const name = this.files?.[0]?.name;
        document.getElementById('file-name').textContent =
            name || 'Choose a new PDF, DOCX, TXT, or image file.';
    });
</script>
@endsection
