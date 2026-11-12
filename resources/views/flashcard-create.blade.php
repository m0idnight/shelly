
@extends('layouts.app')

@section('title', 'Create Flashcards | Shelly')

@section('content')

<div class="flashcard-create-page">

    <a href="{{ route('sets.show', $set) }}" class="back-link">
        ← Back to Study Set
    </a>

    <header class="creation-heading">
        <span class="eyebrow">YOUR STUDY COLLECTION</span>

        <h1>How would you like to create?</h1>

        <p>
            Choose your preferred way to build flashcards for
            <strong>{{ $set->title }}</strong>.
        </p>

        <span class="heading-decoration">୨୧</span>
    </header>

    <section class="creation-options">

        <!-- CREATE WITH SHELLY -->
        <article class="creation-card shelly-card">

            <div class="card-decoration">✦</div>

            <div class="option-icon shelly-icon">
                <span>✧</span>
            </div>

            <span class="option-label">SMART GENERATION</span>

            <h2>Create with Shelly</h2>

            <p>
                Let Shelly turn your uploaded study materials into
                helpful question-and-answer flashcards.
            </p>

            <ul class="feature-list">
                <li>Generate cards from your study materials</li>
                <li>Choose how many cards you need</li>
                <li>Review and study your generated cards</li>
            </ul>

            <a
    href="{{ route('sets.flashcards.setup', $set) }}"
    class="creation-button shelly-button"
>
    Create with Shelly
    <span>→</span>
</a>

            <span class="card-note">
                A little help from Shelly
            </span>

        </article>


        <!-- CREATE MANUALLY -->
        <article class="creation-card manual-card">

            <div class="card-decoration">✿</div>

            <div class="option-icon manual-icon">
                <span>✎</span>
            </div>

            <span class="option-label">YOUR OWN QUESTIONS</span>

            <h2>Create Manually</h2>

            <p>
                Write your own questions and answers, and organize
                them into a personalized flashcard collection.
            </p>

            <ul class="feature-list">
                <li>Choose how many cards to create</li>
                <li>Write your own questions and answers</li>
                <li>Save drafts and continue later</li>
            </ul>

            <a
                href="#"
                class="creation-button manual-button"
                aria-disabled="true"
            >
                Coming in the next step
                <span>→</span>
            </a>

            <span class="card-note">
                Made by you, at your own pace
            </span>

        </article>

    </section>

</div>

@endsection

@push('styles')
<style>
    .flashcard-create-page {
        max-width: 1050px;
        margin: 0 auto;
        padding: 15px 0 55px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #ad7189;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: color .2s ease;
    }

    .back-link:hover {
        color: #87546a;
    }

    .creation-heading {
        max-width: 650px;
        margin: 55px auto 42px;
        text-align: center;
    }

    .eyebrow {
        color: #b97891;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2.5px;
    }

    .creation-heading h1 {
        margin: 13px 0 12px;
        color: #604653;
        font-family: 'Playfair Display', serif;
        font-size: clamp(30px, 5vw, 43px);
        font-weight: 700;
        line-height: 1.2;
    }

    .creation-heading p {
        color: #a58c96;
        font-size: 14px;
        line-height: 1.8;
    }

    .creation-heading strong {
        color: #a96e85;
    }

    .heading-decoration {
        display: block;
        margin-top: 18px;
        color: #d99ab0;
        font-size: 22px;
        letter-spacing: 7px;
    }

    .creation-options {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .creation-card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: 430px;
        padding: 34px;
        border: 1px solid #efd9e1;
        border-radius: 18px;
        background: rgba(255, 253, 253, .88);
        box-shadow: 0 12px 35px rgba(151, 95, 115, .07);
        overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .creation-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 17px 40px rgba(151, 95, 115, .11);
    }

    .shelly-card {
        background:
            radial-gradient(circle at 100% 0%, #fce8ef, transparent 38%),
            #fffdfd;
    }

    .manual-card {
        background:
            radial-gradient(circle at 100% 0%, #f9edf0, transparent 38%),
            #fffdfd;
    }

    .card-decoration {
        position: absolute;
        top: 20px;
        right: 24px;
        color: #dba6b8;
        font-size: 23px;
    }

    .option-icon {
        display: grid;
        width: 57px;
        height: 57px;
        margin-bottom: 25px;
        place-items: center;
        border: 1px solid #efd3dd;
        border-radius: 16px;
        background: #fff4f7;
        color: #bb7891;
        font-size: 27px;
    }

    .manual-icon {
        background: #fcf0f1;
    }

    .option-label {
        color: #b47a91;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .creation-card h2 {
        margin: 10px 0;
        color: #604653;
        font-family: 'Playfair Display', serif;
        font-size: 27px;
    }

    .creation-card > p {
        margin: 0;
        color: #a58c96;
        font-size: 13px;
        line-height: 1.8;
    }

    .feature-list {
        display: grid;
        gap: 13px;
        margin: 25px 0 28px;
        padding: 0;
        list-style: none;
    }

    .feature-list li {
        position: relative;
        padding-left: 23px;
        color: #806b74;
        font-size: 12px;
        line-height: 1.6;
    }

    .feature-list li::before {
        content: "✧";
        position: absolute;
        left: 0;
        color: #cf91a7;
        font-size: 14px;
    }

    .creation-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
        margin-top: auto;
        padding: 15px 18px;
        border: 1px solid #e9c5d2;
        border-radius: 9px;
        background: #f8e6ed;
        color: #9f627b;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: not-allowed;
        opacity: .75;
    }

    .creation-button span {
        font-size: 17px;
    }

    .manual-button {
        background: #f9edf0;
    }

    .card-note {
        display: block;
        margin-top: 14px;
        color: #b39ba3;
        font-family: 'Playfair Display', serif;
        font-size: 12px;
        font-style: italic;
        text-align: center;
    }

    @media (max-width: 700px) {
        .flashcard-create-page {
            padding-top: 5px;
        }

        .creation-heading {
            margin: 38px auto 30px;
        }

        .creation-options {
            grid-template-columns: 1fr;
        }

        .creation-card {
            min-height: 400px;
            padding: 27px;
        }
    }
</style>
@endpush
