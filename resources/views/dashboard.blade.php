
@extends('layouts.app')

@section('title', 'Shelly — My Study Corner')

@section('content')

<div class="shelly-planner">

    {{-- Decorative banner --}}
    <section class="planner-banner" aria-label="Shelly study banner">
        <div class="banner-pattern"></div>
        <div class="banner-center">
            <span class="banner-small">A LITTLE SPACE FOR LEARNING</span>
            <span class="banner-title">study, bloom & grow</span>
            <span class="banner-subtitle">your own little study world</span>
        </div>
        <span class="banner-bow banner-bow-one"></span>
        <span class="banner-bow banner-bow-two"></span>
        <span class="banner-flower banner-flower-one">✿</span>
        <span class="banner-flower banner-flower-two">✿</span>
        <span class="banner-butterfly">♡</span>
    </section>

    {{-- Page heading --}}
    <div class="planner-heading">
        <div>
            <span class="heading-kicker">WELCOME TO SHELLY</span>
            <h1>study planner <span>୨୧</span></h1>
            <p>A calm little place to organize your learning.</p>
        </div>

        <a href="{{ route('sets.create') }}" class="add-set-button">
            <span>+</span> New study set
        </a>
    </div>

    {{-- Main two-column layout --}}
    <div class="planner-layout">

        {{-- LEFT SIDEBAR --}}
        <aside class="planner-sidebar">

            {{-- Character illustration area --}}
            <section class="sidebar-illustration">
                <div class="illustration-gingham"></div>

                <div class="illustration-bunny">
                    <div class="bunny-ear left"></div>
                    <div class="bunny-ear right"></div>
                    <div class="bunny-head">
                        <span class="bunny-eye left"></span>
                        <span class="bunny-eye right"></span>
                        <span class="bunny-nose"></span>
                        <span class="bunny-cheek left"></span>
                        <span class="bunny-cheek right"></span>
                    </div>
                    <div class="bunny-ribbon">
                        <span></span>
                    </div>
                </div>

                <div class="illustration-caption">
                    <span>take it one page at a time</span>
                </div>
            </section>

            {{-- Daily progress --}}
            <section class="sidebar-widget">
                <div class="widget-heading">
                    <span class="widget-flower">✿</span>
                    <h3>daily progress</h3>
                </div>

                <div class="sidebar-progress">
                    <div class="sidebar-progress-top">
                        <span>Today's goal</span>
                        <strong>{{ $percent }}%</strong>
                    </div>

                    <div class="sidebar-progress-track">
                        <span style="width: {{ $percent }}%"></span>
                    </div>

                    <p>
                        {{ min($done, $goal) }} of {{ $goal }} activities completed
                    </p>
                </div>

                <div class="sidebar-stat-row">
                    <span>Study streak</span>
                    <strong>{{ $streak }} {{ $streak === 1 ? 'day' : 'days' }}</strong>
                </div>

                <div class="sidebar-stat-row">
                    <span>Total points</span>
                    <strong>{{ number_format($points) }}</strong>
                </div>
            </section>

            {{-- Weekly activity --}}
            <section class="sidebar-widget">
                <div class="widget-heading">
                    <span class="widget-flower">✿</span>
                    <h3>weekly activity</h3>
                </div>

                <div class="sidebar-week">
                    @foreach ($week as $d)
                        <div class="sidebar-week-day">
                            <span class="sidebar-week-dot
                                {{ $d['done'] ? 'completed' : '' }}
                                {{ $d['today'] ? 'current' : '' }}">
                                @if ($d['done'])
                                    ✓
                                @endif
                            </span>
                            <span class="{{ $d['today'] ? 'current-day' : '' }}">
                                {{ $d['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Quick links --}}
            <section class="sidebar-widget sidebar-links">
                <div class="widget-heading">
                    <span class="widget-flower">✿</span>
                    <h3>study corner</h3>
                </div>

                <a href="{{ route('dashboard') }}">My dashboard</a>
                <a href="{{ route('sets.create') }}">Upload materials</a>
                <a href="#my-study-sets">My study sets</a>

                @if ($sets->count())
                    <a href="{{ route('sets.flashcards', $sets->first()) }}">
                        Flashcards
                    </a>
                    <a href="{{ route('sets.quiz', $sets->first()) }}">
                        Practice quiz
                    </a>
                @endif
            </section>

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="planner-main">

            {{-- Navigation-style labels --}}
            <div class="planner-tabs">
                <a href="#my-study-sets" class="planner-tab active">
                    <span class="tab-icon">▤</span>
                    My studies
                </a>
                <a href="#study-tools" class="planner-tab">
                    <span class="tab-icon">▧</span>
                    Study tools
                </a>
                <span class="planner-tab-note">your learning collection</span>
            </div>

            {{-- Study sets table --}}
            <section class="planner-table-section" id="my-study-sets">
                <div class="table-heading">
                    <div>
                        <span class="table-heading-icon">☷</span>
                        <h2>my study sets <span>୨୧</span></h2>
                    </div>
                    <a href="{{ route('sets.create') }}" class="table-add-link">
                        + Add new
                    </a>
                </div>

                @if ($sets->count())
                    <div class="study-table-wrap">
                        <table class="study-table">
                            <thead>
                                <tr>
                                    <th>Study material</th>
                                    <th>Quiz activity</th>
                                    <th>Accuracy</th>
                                    <th>Open</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($sets as $s)
                                    <tr>
                                        <td>
                                            <a href="{{ route('sets.show', $s) }}"
                                               class="table-set-name">
                                                {{ $s->title }}
                                            </a>
                                        </td>

                                        <td>
                                            @if ($s->answered)
                                                <span class="table-pill in-progress">
                                                    {{ $s->answered }} answers
                                                </span>
                                            @else
                                                <span class="table-pill not-started">
                                                    Not started
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="accuracy-label">
                                                {{ $s->accuracy }}%
                                            </span>
                                        </td>

                                        <td>
                                            <a href="{{ route('sets.show', $s) }}"
                                               class="table-open">
                                                View →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="planner-empty">
                        <div class="empty-bow"></div>
                        <h3>Your study collection is waiting.</h3>
                        <p>Upload your first learning material to get started.</p>
                        <a href="{{ route('sets.create') }}" class="add-set-button">
                            + Create a study set
                        </a>
                    </div>
                @endif
            </section>

            {{-- Study tools --}}
            <section class="tools-section" id="study-tools">
                <div class="main-section-heading">
                    <div>
                        <span class="heading-kicker">YOUR LEARNING SPACE</span>
                        <h2>study tools</h2>
                    </div>
                </div>

                <div class="tools-grid">
                    @if ($sets->count())
                        <a href="{{ route('sets.flashcards', $sets->first()) }}"
                           class="tool-card">
                            <span class="tool-art flashcard-art">
                                <span></span>
                            </span>
                            <h3>Flashcards</h3>
                            <p>Review and remember key ideas.</p>
                            <span class="tool-link">Start reviewing →</span>
                        </a>

                        <a href="{{ route('sets.quiz', $sets->first()) }}"
                           class="tool-card">
                            <span class="tool-art quiz-art">
                                <span>?</span>
                            </span>
                            <h3>Practice quiz</h3>
                            <p>Check what you have learned.</p>
                            <span class="tool-link">Take a quiz →</span>
                        </a>

                        <a href="{{ route('sets.show', $sets->first()) }}"
                           class="tool-card">
                            <span class="tool-art notes-art">
                                <span></span>
                            </span>
                            <h3>Study summaries</h3>
                            <p>Read through your generated notes.</p>
                            <span class="tool-link">View summaries →</span>
                        </a>
                    @else
                        <a href="{{ route('sets.create') }}" class="tool-card">
                            <span class="tool-art upload-art">
                                <span>+</span>
                            </span>
                            <h3>Upload materials</h3>
                            <p>Create your first study set.</p>
                            <span class="tool-link">Get started →</span>
                        </a>
                    @endif
                </div>
            </section>

            {{-- Study collection gallery --}}
            <section class="gallery-section">
                <div class="main-section-heading">
                    <div>
                        <span class="heading-kicker">KEEP EVERYTHING TOGETHER</span>
                        <h2>my learning collection</h2>
                    </div>

                    <a href="{{ route('sets.create') }}" class="table-add-link">
                        Add set +
                    </a>
                </div>

                @if ($sets->count())
                    <div class="collection-grid">
                        @foreach ($sets as $s)
                            <a href="{{ route('sets.show', $s) }}"
                               class="collection-card">

                                <div class="collection-cover">
                                    <span class="cover-decoration">✿</span>
                                    <span class="cover-label">SHELLY STUDY NOTES</span>
                                    <span class="cover-title">{{ $s->title }}</span>
                                    <span class="cover-bottom">a little progress every day</span>
                                </div>

                                <div class="collection-info">
                                    <h3>{{ $s->title }}</h3>
                                    <p>
                                        {{ $s->answered }} quiz answers
                                        <span>·</span>
                                        {{ $s->accuracy }}% accuracy
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="gallery-empty">
                        Your learning collection will appear here after you upload materials.
                    </p>
                @endif
            </section>

            {{-- Encouragement --}}
            <section class="planner-note">
                <span class="note-bow"></span>
                <div>
                    <span class="heading-kicker">A LITTLE REMINDER</span>
                    <p>{{ $cheer }}</p>
                </div>
                <span class="note-flower">✿</span>
            </section>

        </main>
    </div>
</div>

@endsection

@push('styles')
<style>
    .shelly-planner {
        color: #55454b;
        padding-bottom: 35px;
        font-family: "Nunito", sans-serif;
    }

    /* Banner */

    .planner-banner {
        position: relative;
        height: 205px;
        overflow: hidden;
        border: 1px solid #f0d9e1;
        border-radius: 5px;
        background-color: #f9e8ed;
        background-image:
            linear-gradient(45deg, rgba(255,255,255,.55) 25%, transparent 25%),
            linear-gradient(-45deg, rgba(255,255,255,.55) 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, rgba(255,255,255,.55) 75%),
            linear-gradient(-45deg, transparent 75%, rgba(255,255,255,.55) 75%);
        background-size: 32px 32px;
        background-position: 0 0, 0 16px, 16px -16px, -16px 0;
    }

    .banner-pattern {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(255,255,255,.55), transparent 35%, rgba(255,255,255,.45)),
            linear-gradient(0deg, rgba(245,208,220,.65), transparent 55%);
    }

    .banner-center {
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: max-content;
        max-width: 80%;
        padding: 19px 34px;
        border: 1px solid rgba(226,184,199,.7);
        background: rgba(255,250,251,.83);
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .banner-small {
        color: #b47b91;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .2em;
    }

    .banner-title {
        margin-top: 7px;
        color: #795565;
        font-family: Georgia, serif;
        font-size: clamp(23px, 4vw, 37px);
        font-weight: bold;
        letter-spacing: .07em;
    }

    .banner-subtitle {
        margin-top: 5px;
        color: #a78694;
        font-family: Georgia, serif;
        font-size: 11px;
        font-style: italic;
        letter-spacing: .08em;
    }

    .banner-bow {
        position: absolute;
        z-index: 2;
        width: 45px;
        height: 25px;
    }

    .banner-bow::before,
    .banner-bow::after {
        position: absolute;
        top: 0;
        width: 22px;
        height: 22px;
        border: 2px solid #d99bb0;
        border-radius: 70% 35% 70% 35%;
        background: rgba(255,248,250,.65);
        content: "";
    }

    .banner-bow::before {
        left: 0;
        transform: rotate(20deg);
    }

    .banner-bow::after {
        right: 0;
        transform: rotate(70deg);
    }

    .banner-bow-one { top: 25px; left: 10%; }
    .banner-bow-two { right: 12%; bottom: 25px; transform: scale(.75); }

    .banner-flower {
        position: absolute;
        color: #d69bb0;
        font-size: 38px;
        opacity: .7;
    }

    .banner-flower-one { top: 15px; right: 22%; }
    .banner-flower-two { bottom: 12px; left: 23%; font-size: 25px; }

    .banner-butterfly {
        position: absolute;
        top: 42px;
        right: 8%;
        color: #c984a0;
        font-size: 32px;
        transform: rotate(-25deg);
    }

    /* Heading */

    .planner-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
        margin: 34px 0 24px;
    }

    .heading-kicker {
        color: #b47b91;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .18em;
    }

    .planner-heading h1 {
        margin: 6px 0 5px;
        color: #393236;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 35px;
        font-weight: bold;
        letter-spacing: .1em;
    }

    .planner-heading h1 span {
        color: #d58ba6;
        font-size: 24px;
    }

    .planner-heading p {
        margin: 0;
        color: #9c8991;
        font-family: Georgia, serif;
        font-size: 13px;
    }

    .add-set-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid #d99bb0;
        border-radius: 4px;
        background: #f8e8ee;
        color: #8e5d72;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        transition: .2s ease;
        white-space: nowrap;
    }

    .add-set-button:hover {
        background: #f2d9e3;
    }

    .add-set-button span {
        font-size: 17px;
        line-height: 1;
    }

    /* Main layout */

    .planner-layout {
        display: grid;
        grid-template-columns: 245px minmax(0, 1fr);
        align-items: start;
        gap: 34px;
    }

    .planner-sidebar {
        display: flex;
        flex-direction: column;
        gap: 14px;
        min-width: 0;
    }

    /* Sidebar illustration */

    .sidebar-illustration {
        position: relative;
        height: 240px;
        overflow: hidden;
        border: 1px solid #f0dce4;
        background: #f9e5ed;
    }

    .illustration-gingham {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(45deg, #f4cbd9 25%, transparent 25%),
            linear-gradient(-45deg, #f4cbd9 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, #f4cbd9 75%),
            linear-gradient(-45deg, transparent 75%, #f4cbd9 75%);
        background-size: 23px 23px;
        background-position: 0 0, 0 11.5px, 11.5px -11.5px, -11.5px 0;
        opacity: .6;
    }

    .illustration-bunny {
        position: absolute;
        top: 35px;
        left: 50%;
        width: 120px;
        height: 155px;
        transform: translateX(-50%);
    }

    .bunny-ear {
        position: absolute;
        top: 0;
        width: 38px;
        height: 95px;
        border: 2px solid #d99aae;
        border-radius: 60% 60% 40% 40%;
        background: #fff9fa;
    }

    .bunny-ear.left {
        left: 16px;
        transform: rotate(-13deg);
    }

    .bunny-ear.right {
        right: 16px;
        transform: rotate(13deg);
    }

    .bunny-head {
        position: absolute;
        bottom: 0;
        left: 3px;
        width: 114px;
        height: 100px;
        border: 2px solid #d99aae;
        border-radius: 48% 48% 45% 45%;
        background: #fffafa;
    }

    .bunny-eye {
        position: absolute;
        top: 40px;
        width: 7px;
        height: 10px;
        border-radius: 50%;
        background: #82616e;
    }

    .bunny-eye.left { left: 32px; }
    .bunny-eye.right { right: 32px; }

    .bunny-nose {
        position: absolute;
        top: 55px;
        left: 53px;
        width: 8px;
        height: 7px;
        border-radius: 50%;
        background: #d98da7;
    }

    .bunny-cheek {
        position: absolute;
        top: 59px;
        width: 16px;
        height: 7px;
        border-radius: 50%;
        background: #f2c5d4;
    }

    .bunny-cheek.left { left: 13px; }
    .bunny-cheek.right { right: 13px; }

    .bunny-ribbon {
        position: absolute;
        z-index: 2;
        top: 37px;
        right: 5px;
        width: 30px;
        height: 17px;
        border-radius: 50%;
        background: #d98da7;
        transform: rotate(-12deg);
    }

    .bunny-ribbon::before,
    .bunny-ribbon::after {
        position: absolute;
        top: 1px;
        width: 15px;
        height: 14px;
        border: 2px solid #ca809c;
        border-radius: 70% 35% 70% 35%;
        background: #f4cfdb;
        content: "";
    }

    .bunny-ribbon::before { left: -10px; transform: rotate(20deg); }
    .bunny-ribbon::after { right: -10px; transform: rotate(70deg); }

    .illustration-caption {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 9px;
        background: rgba(255,249,251,.9);
        color: #a8788d;
        font-family: Georgia, serif;
        font-size: 11px;
        font-style: italic;
        text-align: center;
        letter-spacing: .05em;
    }

    /* Sidebar widgets */

    .sidebar-widget {
        padding: 16px 15px;
        border: 1px solid #f0dfe5;
        border-radius: 4px;
        background: #fff7fa;
    }

    .widget-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 14px;
    }

    .widget-flower {
        color: #d68eaa;
        font-size: 17px;
    }

    .widget-heading h3 {
        margin: 0;
        color: #59434e;
        font-family: Georgia, serif;
        font-size: 14px;
        font-weight: normal;
        letter-spacing: .12em;
    }

    .sidebar-progress-top {
        display: flex;
        justify-content: space-between;
        color: #967b88;
        font-size: 11px;
    }

    .sidebar-progress-top strong {
        color: #a65e7b;
    }

    .sidebar-progress-track {
        height: 7px;
        margin-top: 9px;
        overflow: hidden;
        border-radius: 20px;
        background: #f0dce4;
    }

    .sidebar-progress-track span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: #d58ba6;
    }

    .sidebar-progress p {
        margin: 7px 0 13px;
        color: #a48d97;
        font-size: 10px;
    }

    .sidebar-stat-row {
        display: flex;
        justify-content: space-between;
        padding: 9px 0;
        border-top: 1px solid #f0e1e6;
        color: #947d88;
        font-size: 11px;
    }

    .sidebar-stat-row strong {
        color: #85586e;
    }

    /* Weekly sidebar */

    .sidebar-week {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .sidebar-week-day {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 7px;
        color: #a58e98;
        font-size: 10px;
    }

    .sidebar-week-dot {
        display: grid;
        place-items: center;
        width: 25px;
        height: 25px;
        border: 1px solid #edd9e1;
        border-radius: 50%;
        background: #fffdfd;
        font-size: 11px;
    }

    .sidebar-week-dot.completed {
        border-color: #dba5b9;
        background: #f2dce5;
        color: #a65e7b;
    }

    .sidebar-week-dot.current {
        outline: 2px solid #edc4d2;
        outline-offset: 2px;
    }

    .current-day { color: #a65e7b; font-weight: bold; }

    .sidebar-links {
        display: flex;
        flex-direction: column;
    }

    .sidebar-links a {
        padding: 9px 2px;
        border-top: 1px solid #f0e1e6;
        color: #8e6e7d;
        font-family: Georgia, serif;
        font-size: 12px;
        text-decoration: none;
        transition: color .2s ease;
    }

    .sidebar-links a:hover {
        color: #c47795;
        padding-left: 6px;
    }

    /* Main column */

    .planner-main {
        min-width: 0;
    }

    .planner-tabs {
        display: flex;
        align-items: center;
        gap: 22px;
        min-height: 40px;
        border-bottom: 1px solid #e9e1e4;
    }

    .planner-tab {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        align-self: stretch;
        padding: 0 7px 10px;
        border-bottom: 2px solid transparent;
        color: #93858c;
        font-family: Georgia, serif;
        font-size: 12px;
        text-decoration: none;
    }

    .planner-tab.active {
        border-color: #bd7792;
        color: #6f5260;
    }

    .tab-icon {
        font-family: Arial, sans-serif;
        font-size: 15px;
    }

    .planner-tab-note {
        margin-left: auto;
        color: #b09da5;
        font-family: Georgia, serif;
        font-size: 10px;
        font-style: italic;
    }

    /* Table */

    .planner-table-section {
        margin-top: 20px;
        scroll-margin-top: 20px;
    }

    .table-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 12px;
    }

    .table-heading > div {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-heading-icon {
        color: #c66e91;
        font-size: 21px;
    }

    .table-heading h2 {
        margin: 0;
        color: #44383d;
        font-family: Georgia, serif;
        font-size: 20px;
        letter-spacing: .13em;
    }

    .table-heading h2 span {
        color: #c77e99;
        font-size: 16px;
    }

    .table-add-link {
        color: #a66d85;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .table-add-link:hover { text-decoration: underline; }

    .study-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .study-table {
        width: 100%;
        border-collapse: collapse;
        color: #66575e;
        font-family: Georgia, serif;
        font-size: 12px;
    }

    .study-table th {
        padding: 11px 9px;
        border-bottom: 1px solid #e8e0e3;
        color: #998a91;
        font-size: 11px;
        font-weight: normal;
        text-align: left;
        white-space: nowrap;
    }

    .study-table td {
        padding: 12px 9px;
        border-bottom: 1px solid #eee8eb;
        vertical-align: middle;
    }

    .study-table tbody tr:hover {
        background: #fff8fa;
    }

    .table-set-name {
        color: #55434c;
        text-decoration: underline;
        text-decoration-color: #e2c8d2;
        text-underline-offset: 3px;
    }

    .table-pill {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-family: "Nunito", sans-serif;
        font-size: 10px;
        white-space: nowrap;
    }

    .table-pill.in-progress {
        background: #f0e3f2;
        color: #886a91;
    }

    .table-pill.not-started {
        background: #f7e0e9;
        color: #a96c84;
    }

    .accuracy-label {
        color: #8e6579;
        font-family: "Nunito", sans-serif;
        font-weight: 700;
    }

    .table-open {
        color: #ad6f89;
        font-family: "Nunito", sans-serif;
        font-size: 11px;
        text-decoration: none;
        white-space: nowrap;
    }

    .table-open:hover { text-decoration: underline; }

    .planner-empty {
        padding: 35px 20px;
        border: 1px solid #f0dfe5;
        background: #fff9fb;
        text-align: center;
    }

    .planner-empty h3 {
        color: #795568;
        font-family: Georgia, serif;
        font-size: 19px;
    }

    .planner-empty p {
        color: #a18b95;
        font-size: 12px;
    }

    .empty-bow {
        width: 32px;
        height: 20px;
        margin: 0 auto;
        border: 3px solid #d99bb0;
        border-radius: 50%;
    }

    /* Study tools */

    .tools-section,
    .gallery-section {
        margin-top: 37px;
        scroll-margin-top: 20px;
    }

    .main-section-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 15px;
    }

    .main-section-heading h2 {
        margin: 5px 0 0;
        color: #44383d;
        font-family: Georgia, serif;
        font-size: 22px;
        letter-spacing: .08em;
    }

    .tools-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .tool-card {
        display: flex;
        flex-direction: column;
        min-height: 175px;
        padding: 15px;
        border: 1px solid #eee0e5;
        border-radius: 4px;
        background: #fffafb;
        text-decoration: none;
        transition: .2s ease;
    }

    .tool-card:hover {
        border-color: #dba8ba;
        background: #fff5f8;
        transform: translateY(-2px);
    }

    .tool-art {
        position: relative;
        display: grid;
        place-items: center;
        width: 38px;
        height: 38px;
        margin-bottom: 12px;
        border: 1px solid #edceda;
        border-radius: 10px;
        background: #f9eaf0;
        color: #b56e8a;
        font-family: Georgia, serif;
        font-size: 20px;
    }

    .flashcard-art span {
        width: 17px;
        height: 21px;
        border: 1px solid #c887a0;
        border-radius: 2px;
        background: #fffafb;
        box-shadow: 4px 3px 0 #e8c1d0;
    }

    .quiz-art span {
        display: grid;
        place-items: center;
        width: 20px;
        height: 20px;
        border: 1px solid #c887a0;
        border-radius: 50%;
        font-size: 13px;
    }

    .notes-art span {
        width: 17px;
        height: 21px;
        border: 1px solid #c887a0;
        background: repeating-linear-gradient(to bottom, #fffafb 0 5px, #edcbd8 6px 7px);
    }

    .tool-card h3 {
        margin: 0 0 5px;
        color: #755465;
        font-family: Georgia, serif;
        font-size: 14px;
    }

    .tool-card p {
        margin: 0;
        color: #a18c96;
        font-size: 10px;
        line-height: 1.6;
    }

    .tool-link {
        margin-top: auto;
        padding-top: 12px;
        color: #b16f8a;
        font-size: 10px;
        font-weight: 800;
    }

    /* Collection gallery */

    .collection-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 13px;
    }

    .collection-card {
        overflow: hidden;
        border: 1px solid #eee0e5;
        border-radius: 4px;
        background: #fff;
        text-decoration: none;
        transition: .2s ease;
    }

    .collection-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(133, 85, 105, .08);
    }

    .collection-cover {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 130px;
        padding: 14px;
        overflow: hidden;
        background-color: #f8e8ee;
        background-image:
            linear-gradient(45deg, rgba(255,255,255,.45) 25%, transparent 25%),
            linear-gradient(-45deg, rgba(255,255,255,.45) 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, rgba(255,255,255,.45) 75%),
            linear-gradient(-45deg, transparent 75%, rgba(255,255,255,.45) 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0;
        text-align: center;
    }

    .cover-decoration {
        position: absolute;
        top: 8px;
        right: 12px;
        color: #d28ba5;
        font-size: 22px;
    }

    .cover-label {
        color: #b47d93;
        font-size: 8px;
        font-weight: 800;
        letter-spacing: .16em;
    }

    .cover-title {
        display: -webkit-box;
        overflow: hidden;
        margin: 10px 0;
        color: #735265;
        font-family: Georgia, serif;
        font-size: 17px;
        font-weight: bold;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .cover-bottom {
        color: #a77d8f;
        font-family: Georgia, serif;
        font-size: 9px;
        font-style: italic;
    }

    .collection-info {
        padding: 12px;
    }

    .collection-info h3 {
        overflow: hidden;
        margin: 0 0 5px;
        color: #755465;
        font-family: Georgia, serif;
        font-size: 13px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .collection-info p {
        margin: 0;
        color: #a18c96;
        font-size: 9px;
    }

    .collection-info p span {
        margin: 0 3px;
        color: #d49bb0;
    }

    .gallery-empty {
        padding: 22px;
        border: 1px solid #eee0e5;
        color: #a18c96;
        font-family: Georgia, serif;
        font-size: 12px;
        text-align: center;
    }

    /* Note */

    .planner-note {
        position: relative;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 35px;
        padding: 19px;
        border: 1px solid #efdce4;
        background: #fff5f8;
    }

    .note-bow {
        position: relative;
        width: 32px;
        height: 25px;
        flex-shrink: 0;
    }

    .note-bow::before,
    .note-bow::after {
        position: absolute;
        top: 2px;
        width: 15px;
        height: 17px;
        border: 2px solid #d99bb0;
        border-radius: 70% 35% 70% 35%;
        content: "";
    }

    .note-bow::before { left: 0; transform: rotate(20deg); }
    .note-bow::after { right: 0; transform: rotate(70deg); }

    .planner-note p {
        margin: 6px 0 0;
        color: #805a6c;
        font-family: Georgia, serif;
        font-size: 14px;
        line-height: 1.6;
    }

    .note-flower {
        margin-left: auto;
        color: #d99bb0;
        font-size: 27px;
    }

    /* Responsive */

    @media (max-width: 850px) {
        .planner-layout {
            grid-template-columns: 205px minmax(0, 1fr);
            gap: 22px;
        }

        .tools-grid,
        .collection-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .study-table { min-width: 550px; }
    }

    @media (max-width: 640px) {
        .planner-banner { height: 160px; }

        .planner-heading {
            align-items: start;
            flex-direction: column;
            margin: 25px 0 20px;
        }

        .planner-heading h1 {
            font-size: 29px;
        }

        .planner-layout {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .planner-sidebar {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 12px;
        }

        .sidebar-illustration {
            grid-row: span 2;
            height: 225px;
        }

        .sidebar-widget {
            padding: 13px 11px;
        }

        .sidebar-links {
            grid-column: 1 / -1;
        }

        .planner-tabs { gap: 12px; }

        .planner-tab { font-size: 11px; }

        .planner-tab-note { display: none; }

        .table-heading h2 { font-size: 17px; }

        .tools-grid,
        .collection-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 390px) {
        .planner-sidebar {
            grid-template-columns: 1fr;
        }

        .sidebar-illustration {
            grid-row: auto;
            height: 200px;
        }

        .sidebar-links { grid-column: auto; }

        .tools-grid,
        .collection-grid {
            grid-template-columns: 1fr;
        }

        .banner-center { padding: 15px 20px; }

        .banner-title { font-size: 23px; }
    }
</style>
@endpush
