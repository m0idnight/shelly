
@extends('layouts.app')

@section('title', 'Study Summary — ' . $set->title)

@section('content')

<div class="summary-page">

    <!-- Page heading -->
    <section class="summary-heading">
        <div>
            <span class="page-kicker">♡ Your study notes</span>
            <h1 class="page-title">Study Summary</h1>
            <p class="page-description">
                A little collection of the important things to remember.
            </p>
        </div>

        <div class="summary-decoration" aria-hidden="true">
            ୨୧
        </div>
    </section>

    <!-- Material information -->
    <section class="summary-material panel">
        <div class="material-icon">✧</div>

        <div class="material-details">
            <span class="material-label">SUMMARY FOR</span>
            <h2>{{ $set->title }}</h2>
            <p>Your uploaded study material</p>
        </div>

        <a href="{{ route('sets.show', $set) }}" class="summary-back">
            View material →
        </a>
    </section>

    <!-- Success message -->
    @if (session('success'))
        <div class="summary-alert success-alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error message -->
    @if ($errors->any())
        <div class="summary-alert error-alert">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('error'))
        <div class="summary-alert error-alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Summary content -->
    <section class="summary-content panel">

        <div class="summary-content-heading">
            <div>
                <span class="page-kicker">✧ Your generated notes</span>
                <h2>Let's review what matters.</h2>
            </div>

            <span class="summary-sparkle">✦</span>
        </div>

        @if ($set->summary)

            <div class="markdown-content">
                {!! Str::markdown($set->summary, ['html_input' => 'escape']) !!}
            </div>

            <div class="summary-footer">
                <p>♡ Take your time. You're doing lovely!</p>

                <a href="{{ route('sets.summary.setup', $set) }}"
                   class="button regenerate-button">
                    ✧ Regenerate Summary
                </a>
            </div>

        @else

            <div class="summary-empty">
                <div class="empty-decoration">୨୧</div>

                <h3>Your study summary is waiting!</h3>

                <p>
                    Let Shelly turn your uploaded material into
                    clear, organized study notes.
                </p>

                <a href="{{ route('sets.summary.setup', $set) }}"
                   class="button generate-button">
                    ✨ Generate My Summary
                </a>
            </div>

        @endif

    </section>

    <div class="summary-bottom-note">
        <span>♡</span>
        Small steps still bring you closer to your goals.
        <span>♡</span>
    </div>

</div>

<style>
    .summary-page {
        max-width: 920px;
        margin: 0 auto;
    }

    /* Heading */

    .summary-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 25px;
    }

    .summary-heading .page-title {
        margin: 7px 0;
    }

    .summary-decoration {
        display: grid;
        place-items: center;
        width: 65px;
        height: 65px;
        border: 1px solid #efd4df;
        border-radius: 50%;
        background: #fff1f6;
        color: #cf91aa;
        font-size: 30px;
    }

    /* Material information */

    .summary-material {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 19px 22px;
        margin-bottom: 20px;
    }

    .material-icon {
        display: grid;
        place-items: center;
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        border-radius: 15px;
        background: #fff0f5;
        color: #c47e9b;
        font-size: 23px;
    }

    .material-details {
        min-width: 0;
        flex: 1;
    }

    .material-label {
        color: #b98a9d;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .14em;
    }

    .material-details h2 {
        margin: 4px 0;
        color: #795568;
        font-family: Georgia, serif;
        font-size: 17px;
        overflow-wrap: anywhere;
    }

    .material-details p {
        margin: 0;
        color: #a18c96;
        font-size: 11px;
    }

    .summary-back {
        flex-shrink: 0;
        color: #b16f8a;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
    }

    .summary-back:hover {
        text-decoration: underline;
    }

    /* Alerts */

    .summary-alert {
        margin-bottom: 18px;
        padding: 13px 16px;
        border-radius: 12px;
        font-size: 13px;
    }

    .success-alert {
        border: 1px solid #cce5d5;
        background: #f1faf4;
        color: #527e61;
    }

    .error-alert {
        border: 1px solid #f0cbd7;
        background: #fff2f5;
        color: #a75c73;
    }

    /* Main summary */

    .summary-content {
        min-height: 350px;
        padding: 30px;
    }

    .summary-content-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0e2e7;
        margin-bottom: 25px;
    }

    .summary-content-heading h2 {
        margin: 7px 0 0;
        color: #795568;
        font-family: Georgia, serif;
        font-size: 23px;
    }

    .summary-sparkle {
        color: #d99bb0;
        font-size: 27px;
    }

    /* Generated markdown */

    .markdown-content {
        color: #755d69;
        font-size: 14px;
        line-height: 1.9;
        overflow-wrap: anywhere;
    }

    .markdown-content h1,
    .markdown-content h2,
    .markdown-content h3 {
        margin-top: 27px;
        margin-bottom: 12px;
        color: #795568;
        font-family: Georgia, serif;
    }

    .markdown-content h1 { font-size: 25px; }
    .markdown-content h2 { font-size: 21px; }
    .markdown-content h3 { font-size: 17px; }

    .markdown-content p {
        margin: 12px 0;
    }

    .markdown-content ul,
    .markdown-content ol {
        padding-left: 25px;
        margin: 12px 0;
    }

    .markdown-content li {
        padding-left: 4px;
        margin-bottom: 8px;
    }

    .markdown-content strong {
        color: #a65f7b;
    }

    .markdown-content blockquote {
        padding: 12px 17px;
        border-left: 3px solid #d99bb0;
        border-radius: 0 10px 10px 0;
        background: #fff5f8;
        color: #805a6c;
    }

    .markdown-content hr {
        border: 0;
        border-top: 1px solid #f0e2e7;
        margin: 22px 0;
    }

    /* Summary footer */

    .summary-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        padding-top: 23px;
        border-top: 1px solid #f0e2e7;
        margin-top: 30px;
    }

    .summary-footer p {
        margin: 0;
        color: #a17e8e;
        font-family: Georgia, serif;
        font-size: 13px;
        font-style: italic;
    }

    .regenerate-button,
    .generate-button {
        font-size: 12px;
        text-decoration: none;
    }

    /* Empty state */

    .summary-empty {
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        min-height: 270px;
        padding: 25px;
        text-align: center;
    }

    .empty-decoration {
        display: grid;
        place-items: center;
        width: 65px;
        height: 65px;
        border: 1px solid #f0d8e2;
        border-radius: 50%;
        background: #fff2f6;
        color: #ce91aa;
        font-size: 29px;
    }

    .summary-empty h3 {
        margin: 19px 0 8px;
        color: #795568;
        font-family: Georgia, serif;
        font-size: 21px;
    }

    .summary-empty p {
        max-width: 400px;
        margin: 0 0 20px;
        color: #a18c96;
        font-size: 13px;
        line-height: 1.7;
    }

    /* Bottom note */

    .summary-bottom-note {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin: 23px 0 10px;
        color: #a17e8e;
        font-family: Georgia, serif;
        font-size: 12px;
        font-style: italic;
        text-align: center;
    }

    .summary-bottom-note span {
        color: #d99bb0;
    }

    /* Responsive */

    @media (max-width: 600px) {
        .summary-heading {
            align-items: flex-start;
        }

        .summary-decoration {
            width: 48px;
            height: 48px;
            font-size: 23px;
        }

        .summary-material {
            flex-wrap: wrap;
            padding: 16px;
        }

        .summary-back {
            width: 100%;
            padding-top: 10px;
            border-top: 1px solid #f0e2e7;
        }

        .summary-content {
            padding: 20px;
        }

        .summary-content-heading h2 {
            font-size: 19px;
        }

        .summary-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .summary-footer .button {
            width: 100%;
            text-align: center;
        }
    }
</style>

@endsection
