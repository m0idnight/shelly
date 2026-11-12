
@extends('layouts.app')

@section('title', 'Study Summary — ' . $set->title)

@section('content')

<div class="summary-page">

    <!-- Page heading -->
    <section class="summary-heading">
        <div>
            <span class="page-kicker">YOUR STUDY SPACE</span>
            <h1 class="page-title">Study Summary</h1>
            <p class="page-description">
                Clear notes, important concepts, and everything worth remembering.
            </p>
        </div>

        <div class="summary-decoration" aria-hidden="true">
            ୨୧
        </div>
    </section>

    <!-- Material information -->
    <section class="summary-material panel">

        <div class="material-icon">
            <span>✧</span>
        </div>

        <div class="material-details">
            <span class="material-label">SUMMARY FOR</span>
            <h2>{{ $set->title }}</h2>
            <p>Your uploaded study material</p>
        </div>

        <a href="{{ route('sets.show', $set) }}" class="summary-back">
            View material <span>→</span>
        </a>

    </section>

    <!-- Success message -->
    @if (session('success'))
        <div class="summary-alert success-alert">
            <span class="alert-icon">✓</span>
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
                <span class="page-kicker">YOUR GENERATED NOTES</span>
                <h2>Let's review what matters.</h2>
                <p>Take your time and study at your own pace.</p>
            </div>

            <div class="summary-sparkle" aria-hidden="true">
                ✦
            </div>
        </div>

        @if ($set->summary)

            <div class="summary-status">
                <span class="status-dot"></span>
                Summary ready for review
            </div>

            <div class="markdown-content">
                {!! Str::markdown($set->summary, ['html_input' => 'escape']) !!}
            </div>

            <div class="summary-footer">

                <div class="footer-message">
                    <span class="footer-icon">✧</span>
                    <p>Keep learning, one concept at a time.</p>
                </div>

                <a href="{{ route('sets.summary.setup', $set) }}"
                   class="button regenerate-button">
                    <span>↻</span> Regenerate Summary
                </a>

            </div>

        @else

            <div class="summary-empty">

                <div class="empty-decoration">
                    <span>✧</span>
                </div>

                <span class="empty-label">YOUR NOTES ARE WAITING</span>

                <h3>Let's make studying easier.</h3>

                <p>
                    Let Shelly transform your uploaded material into
                    clear, organized study notes that are easier to understand
                    and review.
                </p>

                <a href="{{ route('sets.summary.setup', $set) }}"
                   class="button generate-button">
                    ✦ Generate My Summary
                </a>

            </div>

        @endif

    </section>

    <!-- Bottom note -->
    <div class="summary-bottom-note">
        <span class="note-decoration">✧</span>
        <p>Small steps still bring you closer to your goals.</p>
        <span class="note-decoration">✧</span>
    </div>

</div>

<script>
    // Make generated Markdown tables responsive.
    document.querySelectorAll('.markdown-content table').forEach(function (table) {

        if (table.parentElement.classList.contains('markdown-table-wrapper')) {
            return;
        }

        const wrapper = document.createElement('div');

        wrapper.className = 'markdown-table-wrapper';

        table.parentNode.insertBefore(wrapper, table);

        wrapper.appendChild(table);

    });
</script>

<style>

    /* ========================================
       MAIN PAGE
    ======================================== */

    .summary-page {
        width: 100%;
        max-width: 920px;
        margin: 0 auto;
    }


    /* ========================================
       PAGE HEADING
    ======================================== */

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
        flex-shrink: 0;
        border: 1px solid #efd4df;
        border-radius: 50%;
        background: #fff1f6;
        color: #cf91aa;
        font-size: 30px;
    }


    /* ========================================
       MATERIAL INFORMATION
    ======================================== */

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
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        color: #b16f8a;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        transition: color .2s ease;
    }

    .summary-back:hover {
        color: #89516c;
    }

    .summary-back span {
        transition: transform .2s ease;
    }

    .summary-back:hover span {
        transform: translateX(3px);
    }


    /* ========================================
       ALERT MESSAGES
    ======================================== */

    .summary-alert {
        display: flex;
        align-items: center;
        gap: 10px;
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

    .alert-icon {
        display: grid;
        place-items: center;
        width: 22px;
        height: 22px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #dcefe2;
        font-size: 12px;
        font-weight: 800;
    }


    /* ========================================
       MAIN SUMMARY CARD
    ======================================== */

    .summary-content {
        min-height: 350px;
        padding: 30px;
    }

    .summary-content-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding-bottom: 22px;
        border-bottom: 1px solid #f0e2e7;
        margin-bottom: 23px;
    }

    .summary-content-heading h2 {
        margin: 7px 0 5px;
        color: #795568;
        font-family: Georgia, serif;
        font-size: 23px;
    }

    .summary-content-heading p {
        margin: 0;
        color: #a18c96;
        font-size: 12px;
    }

    .summary-sparkle {
        display: grid;
        place-items: center;
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        border-radius: 14px;
        background: #fff2f6;
        color: #d99bb0;
        font-size: 23px;
    }


    /* ========================================
       SUMMARY STATUS
    ======================================== */

    .summary-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        padding: 7px 12px;
        border: 1px solid #e5efe7;
        border-radius: 20px;
        background: #f5faf6;
        color: #72947c;
        font-size: 10px;
        font-weight: 800;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #83b28f;
    }


    /* ========================================
       GENERATED MARKDOWN
    ======================================== */

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
        line-height: 1.4;
    }

    .markdown-content h1 {
        font-size: 25px;
    }

    .markdown-content h2 {
        font-size: 21px;
    }

    .markdown-content h3 {
        font-size: 17px;
    }

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
        font-weight: 800;
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


    /* ========================================
       GENERATED STUDY TABLES
    ======================================== */

    
.markdown-table-wrapper {
    width: 100%;
    max-width: 100%;
    overflow-x: auto;
    margin: 22px 0;
    border: 1px solid #f0dfe6;
    border-radius: 13px;
    background: #fffdfd;
    -webkit-overflow-scrolling: touch;
}

.markdown-content table {
    width: 100%;
    min-width: 680px;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    background: #fffdfd;
    font-size: 13px;
}

/* Give each column enough room */
.markdown-content th:nth-child(1),
.markdown-content td:nth-child(1) {
    width: 23%;
}

.markdown-content th:nth-child(2),
.markdown-content td:nth-child(2) {
    width: 42%;
}

.markdown-content th:nth-child(3),
.markdown-content td:nth-child(3) {
    width: 35%;
}

.markdown-content th {
    padding: 14px 16px;
    background: #fbeaf0;
    color: #795568;
    font-size: 11px;
    font-weight: 800;
    text-align: left;
    letter-spacing: .03em;
    border-bottom: 1px solid #efd4df;
    white-space: normal;
    vertical-align: middle;
}

.markdown-content td {
    padding: 13px 16px;
    color: #755d69;
    border-bottom: 1px solid #f3e8ed;
    vertical-align: top;
    line-height: 1.7;
    overflow-wrap: break-word;
    word-break: normal;
    hyphens: none;
}

.markdown-content tr:nth-child(even) td {
    background: #fff8fa;
}

.markdown-content tr:last-child td {
    border-bottom: 0;
}


    .markdown-content th + th,
    .markdown-content td + td {
        border-left: 1px solid #f3e8ed;
    }

    .markdown-content td p:first-child {
        margin-top: 0;
    }

    .markdown-content td p:last-child {
        margin-bottom: 0;
    }

    .markdown-content td ul,
    .markdown-content td ol {
        padding-left: 18px;
    }


    /* ========================================
       SUMMARY FOOTER
    ======================================== */

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

    .footer-message {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .footer-message p {
        margin: 0;
        color: #a17e8e;
        font-family: Georgia, serif;
        font-size: 13px;
        font-style: italic;
    }

    .footer-icon {
        color: #cf91aa;
        font-size: 17px;
    }

    .regenerate-button,
    .generate-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 12px;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .regenerate-button:hover,
    .generate-button:hover {
        transform: translateY(-2px);
    }


    /* ========================================
       EMPTY STATE
    ======================================== */

    .summary-empty {
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        min-height: 290px;
        padding: 30px 20px;
        text-align: center;
    }

    .empty-decoration {
        display: grid;
        place-items: center;
        width: 67px;
        height: 67px;
        margin-bottom: 17px;
        border: 1px solid #f0d8e2;
        border-radius: 20px;
        background: #fff2f6;
        color: #ce91aa;
        font-size: 29px;
    }

    .empty-label {
        color: #bd8da1;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .15em;
    }

    .summary-empty h3 {
        margin: 10px 0;
        color: #795568;
        font-family: Georgia, serif;
        font-size: 23px;
    }

    .summary-empty p {
        max-width: 410px;
        margin: 0 0 22px;
        color: #a18c96;
        font-size: 13px;
        line-height: 1.8;
    }


    /* ========================================
       BOTTOM NOTE
    ======================================== */

    .summary-bottom-note {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 11px;
        margin: 23px 0 10px;
        text-align: center;
    }

    .summary-bottom-note p {
        margin: 0;
        color: #a17e8e;
        font-family: Georgia, serif;
        font-size: 12px;
        font-style: italic;
    }

    .note-decoration {
        color: #d99bb0;
        font-size: 13px;
    }


    /* ========================================
       RESPONSIVE DESIGN
    ======================================== */

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
            padding-top: 12px;
            border-top: 1px solid #f0e2e7;
        }

        .summary-content {
            padding: 20px;
        }

        .summary-content-heading h2 {
            font-size: 19px;
        }

        .summary-content-heading p {
            max-width: 240px;
            line-height: 1.6;
        }

        .summary-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .summary-footer .button {
            width: 100%;
            text-align: center;
        }

        .summary-empty h3 {
            font-size: 20px;
        }

        .markdown-content {
            font-size: 13px;
        }

        .markdown-content table {
            font-size: 12px;
        }

        .markdown-content th,
        .markdown-content td {
            padding: 11px 12px;
        }

        .summary-bottom-note {
            gap: 7px;
        }

        .summary-bottom-note p {
            font-size: 11px;
        }
    }

</style>

@endsection
