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
            Change the title or replace your study materials.
            Your learning space stays yours. 
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
            id="edit-form"
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

            <label for="files">
                Replace study materials <span>(optional)</span>
            </label>

            <div class="file-area">
                <input
                    type="file"
                    id="files"
                    name="files[]"
                    accept=".pdf,.docx,.txt,.pptx,.jpg,.jpeg,.png,.webp"
                    multiple
                >

                <p id="file-name">
                    Choose up to 10 PDF, DOCX, PPTX, TXT, or image files.
                </p>

                <p class="upload-limits">
                    Maximum 25 MB per file · 128 MB combined
                </p>

                <p id="file-error" class="file-error" role="alert" hidden></p>
            </div>

            <p class="note">
                Leave this empty if you only want to change the title.
                Replacing the materials will reset the generated study content.
            </p>

            <div class="actions">
                <a href="{{ route('sets.show', $set) }}" class="cancel-button">
                    Cancel
                </a>

                <button type="submit" class="button">
                    Save changes 
                </button>
            </div>
        </form>
    </section>
</div>

<style>
    .edit-page {
        max-width: 680px;
        margin: auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 25px;
        color: var(--pink-dark);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    .edit-heading {
        margin-bottom: 25px;
    }

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

    .file-area input {
        max-width: 100%;
    }

    .file-area p {
        margin: 10px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .file-area .upload-limits {
        color: #a47d91;
    }

    .file-area .file-error {
        color: #b4234d;
        font-weight: 700;
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
        .edit-card {
            padding: 25px 20px;
        }

        .actions {
            flex-direction: column-reverse;
        }

        .actions > * {
            width: 100%;
        }
    }
</style>

<script>
(function () {
    const fileInput = document.getElementById('files');
    const fileName = document.getElementById('file-name');
    const fileError = document.getElementById('file-error');
    const form = document.getElementById('edit-form');

    if (!fileInput || !fileName || !fileError || !form) {
        return;
    }

    const MAX_FILES = 10;
    const MAX_FILE_SIZE = 25 * 1024 * 1024;
    const MAX_TOTAL_SIZE = 128 * 1024 * 1024;

    const allowedExtensions = [
        'pdf', 'docx', 'pptx', 'txt',
        'jpg', 'jpeg', 'png', 'webp'
    ];

    function formatSize(bytes) {
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function showError(message) {
        fileError.textContent = message;
        fileError.hidden = false;
    }

    function clearError() {
        fileError.textContent = '';
        fileError.hidden = true;
    }

    function validateFiles(files) {
        clearError();

        if (files.length > MAX_FILES) {
            return 'You can select a maximum of 10 files.';
        }

        let totalSize = 0;
        const seenFiles = new Set();

        for (const file of files) {
            const extension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(extension)) {
                return file.name + ' is not a supported file type.';
            }

            if (file.size > MAX_FILE_SIZE) {
                return file.name + ' exceeds the 25 MB per-file limit.';
            }

            totalSize += file.size;

            const duplicateKey = [
                file.name,
                file.size,
                file.lastModified
            ].join('|');

            if (seenFiles.has(duplicateKey)) {
                return file.name + ' has already been selected.';
            }

            seenFiles.add(duplicateKey);
        }

        if (totalSize > MAX_TOTAL_SIZE) {
            return 'Your selected files exceed the combined 128 MB limit.';
        }

        return null;
    }

    fileInput.addEventListener('change', function () {
        const files = Array.from(this.files || []);

        if (files.length === 0) {
            fileName.textContent =
                'Choose up to 10 PDF, DOCX, PPTX, TXT, or image files.';
            clearError();
            return;
        }

        const error = validateFiles(files);

        if (error) {
            showError(error);
            fileName.textContent = 'Please correct your file selection.';
            return;
        }

        clearError();

        fileName.textContent = files.map(function (file) {
            return file.name + ' (' + formatSize(file.size) + ')';
        }).join(', ');
    });

    form.addEventListener('submit', function (event) {
        const files = Array.from(fileInput.files || []);

        if (files.length === 0) {
            return;
        }

        const error = validateFiles(files);

        if (error) {
            event.preventDefault();
            showError(error);
        }
    });
})();
</script>
@endsection
