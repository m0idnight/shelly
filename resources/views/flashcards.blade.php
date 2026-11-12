
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#fff7fa">

    <title>Flashcards | Shelly</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #e7a1b9;
            --pink-dark: #a65f7b;
            --pink-light: #fceaf0;
            --pink-soft: #fff3f6;
            --cream: #fffdfb;
            --ink: #594650;
            --muted: #9b8490;
            --line: #f0dce4;
            --white: #ffffff;
            --sage: #829e86;
            --sage-light: #edf4ec;
            --shadow: 0 12px 35px rgba(166, 95, 123, .08);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 120px;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background:
                radial-gradient(circle at 5% 10%, #fde8ef 0, transparent 340px),
                radial-gradient(circle at 95% 35%, #fff0e9 0, transparent 320px),
                #fff9fa;
            font-family: "Nunito", sans-serif;
        }

        button,
        input,
        textarea,
        select {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            cursor: pointer;
        }

        button:focus-visible,
        a:focus-visible,
        #scene:focus-visible {
            outline: 3px solid #f3c5d6;
            outline-offset: 3px;
        }

        .site-shell {
            width: min(1120px, calc(100% - 48px));
            margin: 0 auto;
        }

        /* DASHBOARD HEADER */

        .site-header {
            position: sticky;
            top: 15px;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            min-height: 82px;
            margin: 15px 0 0;
            padding: 12px 30px;
            border: 1px solid rgba(240, 220, 228, .95);
            border-radius: 28px;
            background: rgba(255, 253, 251, .93);
            box-shadow: 0 8px 30px rgba(166, 95, 123, .09);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            text-decoration: none;
        }

        .brand-mark {
            position: relative;
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            border-radius: 15px;
            background: #f9dce7;
        }

        .bunny-face {
            position: relative;
            width: 22px;
            height: 20px;
            margin-top: 7px;
            border-radius: 48% 48% 45% 45%;
            background: #fffaf9;
        }

        .bunny-ear {
            position: absolute;
            top: -10px;
            width: 7px;
            height: 17px;
            border-radius: 50%;
            background: #fffaf9;
            border: 1px solid #efc6d5;
        }

        .bunny-ear.left {
            left: 2px;
            transform: rotate(-15deg);
        }

        .bunny-ear.right {
            right: 2px;
            transform: rotate(15deg);
        }

        .bunny-face::before,
        .bunny-face::after {
            content: "";
            position: absolute;
            top: 10px;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #98677a;
        }

        .bunny-face::before {
            left: 6px;
        }

        .bunny-face::after {
            right: 6px;
        }

        .brand-name {
            color: #805568;
            font-family: "Playfair Display", serif;
            font-size: 29px;
            font-weight: 700;
            letter-spacing: -.8px;
        }

        .brand-name span {
            color: var(--pink);
        }

        .main-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 21px;
            border-radius: 50px;
            border: 1px solid transparent;
            color: #8d7480;
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
            text-decoration: none;
            transition: background .25s ease, color .25s ease, transform .25s ease;
        }

        .nav-link:hover {
            color: var(--pink-dark);
            background: var(--pink-soft);
        }

        .nav-link.active {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        /* PAGE CONTENT */

        .page-wrap {
            min-height: 65vh;
            padding: 42px 0 65px;
        }

        .back-row {
            display: flex;
            justify-content: flex-end;
            margin: -15px 0 28px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 9px 16px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: rgba(255, 255, 255, .8);
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 800;
            transition: .2s ease;
        }

        .back-link:hover {
            border-color: #e5b5c7;
            background: var(--pink-soft);
            transform: translateY(-1px);
        }

        /* HEADING */

        .heading {
            margin-bottom: 35px;
            text-align: center;
        }

        .eyebrow {
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        h1 {
            margin: 12px 0 8px;
            color: #694c5b;
            font-family: "Playfair Display", serif;
            font-size: clamp(32px, 5vw, 46px);
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -.8px;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
            font-family: "Playfair Display", serif;
            font-size: 15px;
        }

        .ornament {
            margin-top: 17px;
            color: var(--pink);
            font-size: 16px;
            letter-spacing: 8px;
        }

        /* MESSAGES */

        .error-message {
            min-height: 18px;
            margin-top: 13px;
            color: #b75d78;
            font-size: 12px;
            text-align: center;
        }

        .error-message.success {
            color: #79967e;
            font-weight: 800;
        }

        .error-message.warning {
            color: var(--pink-dark);
            font-weight: 800;
        }

        /* STUDY PANEL */

        .study-panel {
            padding: 34px;
            border: 1px solid rgba(240, 220, 228, .95);
            border-radius: 25px;
            background: rgba(255, 255, 255, .82);
            box-shadow: var(--shadow);
        }

        /* STATISTICS */

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 29px;
        }

        .stat {
            padding: 19px 12px;
            border: 1px solid var(--line);
            border-radius: 17px;
            background: #fffafb;
            text-align: center;
        }

        .stat-number {
            display: block;
            font-family: "Playfair Display", serif;
            font-size: 29px;
            font-weight: 700;
        }

        .stat-label {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .stat.known .stat-number {
            color: var(--sage);
        }

        .stat.review .stat-number {
            color: var(--pink-dark);
        }

        .stat.total .stat-number {
            color: var(--ink);
        }

        /* PROGRESS */

        .progress-heading {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .8px;
        }

        .progress-track {
            height: 9px;
            margin-bottom: 34px;
            border-radius: 30px;
            background: #f5e8eb;
            overflow: hidden;
        }

        .progress-fill {
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #e7afc1, #c97d9b);
            transition: width .35s ease;
        }

        .card-area {
            max-width: 690px;
            margin: auto;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .card-label {
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1.3px;
        }

        .counter {
            padding: 8px 14px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: var(--pink-soft);
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }

        /* FLASHCARD */

        #scene {
            height: 340px;
            perspective: 1400px;
            cursor: pointer;
            touch-action: pan-y;
            outline: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        #card {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform .65s cubic-bezier(.2, .7, .3, 1);
        }

        #card.flipped {
            transform: rotateY(180deg);
        }

        .face {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 42px;
            border: 1px solid #edced8;
            border-radius: 23px;
            background:
                radial-gradient(circle at 10% 10%, rgba(249, 230, 237, .9), transparent 40%),
                #fffdfd;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            text-align: center;
            overflow-wrap: anywhere;
            box-shadow: 0 12px 30px rgba(151, 95, 115, .075);
        }

        .face::before {
            content: "✿";
            position: absolute;
            top: 21px;
            left: 25px;
            color: #d99ab0;
            font-size: 19px;
        }

        .face::after {
            content: "✧";
            position: absolute;
            right: 24px;
            bottom: 17px;
            color: #d99ab0;
            font-size: 23px;
        }

        .back {
            transform: rotateY(180deg);
            background:
                radial-gradient(circle at 90% 10%, #fcecf1, transparent 42%),
                #fffafb;
        }

        .face-label {
            margin-bottom: 23px;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .face-content {
            max-width: 100%;
            font-size: clamp(19px, 3.2vw, 26px);
            font-weight: 700;
            line-height: 1.65;
        }

        .flip-hint {
            margin: 18px 0 24px;
            color: var(--muted);
            font-family: "Playfair Display", serif;
            font-size: 13px;
            font-style: italic;
            text-align: center;
        }

        /* RATING BUTTONS */

        .rate-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px;
            margin-bottom: 16px;
        }

        .rate-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 55px;
            padding: 13px 17px;
            border: 1px solid transparent;
            border-radius: 16px;
            font-size: 13px;
            font-weight: 900;
            transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
        }

        .rate-button:disabled {
            opacity: .45;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .rate-button.again {
            border-color: #f0d6df;
            background: #fff0f4;
            color: var(--pink-dark);
        }

        .rate-button.again:not(:disabled):hover {
            background: #fbe5ec;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(185, 111, 142, .1);
        }

        .rate-button.known {
            border-color: #dce8dc;
            background: var(--sage-light);
            color: #78967d;
        }

        .rate-button.known:not(:disabled):hover {
            background: #e4eee2;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(121, 150, 126, .1);
        }

        .swipe-hint {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 11px;
            text-align: center;
        }

        /* PREVIOUS AND NEXT BUTTONS */

        .controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            max-width: 470px;
            margin: 0 auto;
        }

        .nav-button {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            min-height: 57px;
            padding: 13px 22px;
            border: 1px solid #edcfda;
            border-radius: 17px;
            background: linear-gradient(145deg, #fffefe, #fff7fa);
            color: var(--pink-dark);
            font-size: 13px;
            font-weight: 900;
            box-shadow: 0 5px 14px rgba(166, 95, 123, .055);
            transition:
                transform .22s ease,
                background .22s ease,
                border-color .22s ease,
                box-shadow .22s ease;
        }

        .nav-button:hover:not(:disabled) {
            transform: translateY(-3px);
            border-color: #e3afc2;
            background: #fff0f5;
            box-shadow: 0 9px 20px rgba(166, 95, 123, .11);
        }

        .nav-button:active:not(:disabled) {
            transform: translateY(-1px);
        }

        .nav-button:disabled {
            opacity: .4;
            cursor: not-allowed;
            box-shadow: none;
        }

        .nav-button .arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: var(--pink-light);
            color: var(--pink-dark);
            font-size: 16px;
            line-height: 1;
            transition: background .2s ease, color .2s ease;
        }

        .nav-button:hover:not(:disabled) .arrow {
            background: var(--pink);
            color: white;
        }

        .save-error {
            min-height: 18px;
            margin-top: 15px;
            color: #b75d78;
            font-size: 12px;
            text-align: center;
        }

        .save-error.success {
            color: #79967e;
            font-weight: 800;
        }

        .save-error.warning {
            color: var(--pink-dark);
            font-weight: 800;
        }

        /* REVIEW LINK */

        .review-link {
            display: block;
            width: fit-content;
            margin: 24px auto 0;
            padding: 11px 17px;
            border: 1px solid transparent;
            border-radius: 30px;
            color: var(--pink-dark);
            background: var(--pink-soft);
            font-size: 12px;
            font-weight: 900;
            text-align: center;
            transition: .2s ease;
        }

        .review-link:hover {
            border-color: #edcfda;
            background: var(--pink-light);
            transform: translateY(-1px);
        }

        /* ADDITIONAL ACTIONS */

        .action-section {
            margin-top: 38px;
            padding-top: 30px;
            border-top: 1px solid var(--line);
        }

        .action-heading {
            margin-bottom: 19px;
            text-align: center;
        }

        .action-heading h2 {
            margin: 0 0 7px;
            color: #694c5b;
            font-family: "Playfair Display", serif;
            font-size: 23px;
        }

        .action-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
        }

        .flashcard-action-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            max-width: 720px;
            margin: auto;
        }

        .action-card {
            display: flex;
            align-items: center;
            gap: 15px;
            min-height: 94px;
            padding: 19px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fffdfd;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .action-card:hover {
            transform: translateY(-3px);
            border-color: #e3b7c7;
            box-shadow: 0 9px 22px rgba(151, 95, 115, .09);
        }

        .action-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: var(--pink-light);
            color: var(--pink-dark);
            font-size: 21px;
        }

        .action-card.generate .action-icon {
            background: var(--sage-light);
            color: var(--sage);
        }

        .action-text {
            min-width: 0;
        }

        .action-title {
            display: block;
            margin-bottom: 5px;
            color: var(--ink);
            font-size: 13px;
            font-weight: 900;
        }

        .action-description {
            display: block;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .action-arrow {
            margin-left: auto;
            color: var(--pink);
            font-size: 20px;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 60px 22px;
            border: 1px dashed #e9cbd5;
            border-radius: 20px;
            background: #fffafb;
            text-align: center;
        }

        .empty-state .symbol {
            color: var(--pink);
            font-size: 37px;
        }

        .empty-state h2 {
            margin: 14px 0 7px;
            color: #694c5b;
            font-family: "Playfair Display", serif;
            font-size: 26px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        /* GENERATION OVERLAY */

        .generation-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(255, 250, 248, .96);
            backdrop-filter: blur(8px);
        }

        .generation-overlay.active {
            display: flex;
        }

        .generation-content {
            width: 100%;
            max-width: 370px;
            padding: 38px 27px;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: white;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .generation-bow {
            display: inline-block;
            color: var(--pink);
            font-size: 42px;
            animation: bowPulse 1.4s ease-in-out infinite;
        }

        .generation-content h2 {
            margin: 15px 0 8px;
            color: #694c5b;
            font-family: "Playfair Display", serif;
            font-size: 25px;
        }

        .generation-content p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .generation-track {
            height: 6px;
            margin-top: 23px;
            border-radius: 20px;
            background: var(--pink-light);
            overflow: hidden;
        }

        .generation-progress {
            width: 35%;
            height: 100%;
            border-radius: 20px;
            background: var(--pink);
            animation: loadingMove 1.5s ease-in-out infinite;
        }

        @keyframes bowPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.12); }
        }

        @keyframes loadingMove {
            0% { transform: translateX(-110%); }
            100% { transform: translateX(300%); }
        }

        /* FOOTER */

        .site-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            padding: 23px 0 30px;
            border-top: 1px solid var(--line);
            color: #a88d99;
            font-size: 12px;
            text-align: center;
        }

        .butterfly {
            position: relative;
            display: inline-block;
            width: 24px;
            height: 20px;
            flex-shrink: 0;
        }

        .butterfly::before,
        .butterfly::after {
            content: "";
            position: absolute;
            top: 3px;
            width: 10px;
            height: 13px;
            background: #e9b2c5;
        }

        .butterfly::before {
            left: 1px;
            border-radius: 90% 15% 75% 30%;
            transform: rotate(25deg);
        }

        .butterfly::after {
            right: 1px;
            border-radius: 15% 90% 30% 75%;
            transform: rotate(-25deg);
        }

        .ribbon {
            position: relative;
            display: inline-block;
            width: 25px;
            height: 13px;
            flex-shrink: 0;
        }

        .ribbon::before,
        .ribbon::after {
            content: "";
            position: absolute;
            top: 0;
            width: 11px;
            height: 10px;
            border: 2px solid #d98eaa;
            border-radius: 7px 7px 3px 7px;
        }

        .ribbon::before {
            left: 0;
            transform: rotate(25deg);
        }

        .ribbon::after {
            right: 0;
            transform: rotate(-25deg);
        }

        /* RESPONSIVE */

        @media (max-width: 760px) {
            .site-shell {
                width: calc(100% - 30px);
            }

            .site-header {
                top: 10px;
                flex-direction: column;
                align-items: center;
                gap: 12px;
                padding: 15px 16px;
                border-radius: 24px;
            }

            .main-nav {
                width: 100%;
                justify-content: center;
                gap: 3px;
            }

            .nav-link {
                flex: 1;
                min-height: 40px;
                padding: 9px 10px;
                font-size: 13px;
            }

            .page-wrap {
                padding: 30px 0 48px;
            }

            .back-row {
                margin: -5px 0 25px;
            }

            .heading {
                margin-bottom: 27px;
            }

            .study-panel {
                padding: 24px 18px;
                border-radius: 20px;
            }

            .stats {
                gap: 8px;
            }

            .stat {
                padding: 15px 6px;
                border-radius: 13px;
            }

            .stat-number {
                font-size: 25px;
            }

            .stat-label {
                font-size: 8px;
                letter-spacing: .5px;
            }

            #scene {
                height: 290px;
            }

            .face {
                padding: 25px 20px;
                border-radius: 19px;
            }

            .face::before {
                top: 15px;
                left: 17px;
            }

            .face::after {
                right: 17px;
                bottom: 12px;
            }

            .rate-actions {
                gap: 8px;
            }

            .rate-button {
                min-height: 50px;
                padding: 10px 7px;
                font-size: 11px;
            }

            .controls {
                gap: 10px;
            }

            .nav-button {
                min-height: 52px;
                padding: 10px;
                font-size: 12px;
            }

            .flashcard-action-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .action-card {
                min-height: 80px;
                padding: 15px;
            }

            .action-icon {
                width: 41px;
                height: 41px;
            }
        }

        @media (max-width: 380px) {
            .brand-name {
                font-size: 26px;
            }

            .nav-link {
                padding: 8px 6px;
                font-size: 11px;
            }

            .card-label {
                font-size: 9px;
            }

            .counter {
                padding: 7px 10px;
                font-size: 10px;
            }

            #scene {
                height: 265px;
            }

            .study-panel {
                padding: 19px 12px;
            }

            .nav-button {
                gap: 6px;
                font-size: 11px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body>

<div class="site-shell">

    {{-- DASHBOARD-MATCHING NAVIGATION --}}

    <header class="site-header">

        <a class="brand" href="{{ route('dashboard', [], false) }}">
            <span class="brand-mark" aria-hidden="true">
                <span class="bunny-face">
                    <span class="bunny-ear left"></span>
                    <span class="bunny-ear right"></span>
                </span>
            </span>

            <span class="brand-name">shel<span>ly</span></span>
        </a>

        <nav class="main-nav" aria-label="Main navigation">

            <a
                href="{{ route('dashboard', [], false) }}"
                class="nav-link"
            >
                Home
            </a>

            <a
                href="{{ route('sets.create', [], false) }}"
                class="nav-link"
            >
                Upload materials
            </a>

            <a
                href="{{ route('dashboard', [], false) }}#my-study-sets"
                class="nav-link active"
                aria-current="page"
            >
                My studies
            </a>

        </nav>

    </header>

    <main class="page-wrap">

        {{-- BACK TO STUDY SET --}}

        <div class="back-row">
            <a href="{{ route('sets.show', $set) }}" class="back-link">
                <span aria-hidden="true">←</span>
                Back to study set
            </a>
        </div>

        {{-- PAGE HEADING --}}

        <section class="heading">
            <span class="eyebrow">YOUR LITTLE STUDY CORNER</span>

            <h1>Flashcard Study</h1>

            <p class="subtitle">{{ $set->title }}</p>

            <div class="ornament">✧ ✿ ୨୧ ✿ ✧</div>
        </section>

        @if ($errors->any())
            <p class="error-message">{{ $errors->first() }}</p>
        @endif

        @if (session('success'))
            <p class="error-message success">{{ session('success') }}</p>
        @endif

        <section class="study-panel">

            {{-- PROGRESS STATISTICS --}}

            <section class="stats">

                <div class="stat known">
                    <span class="stat-number" id="knownCount">{{ $knownCount }}</span>
                    <span class="stat-label">Known</span>
                </div>

                <div class="stat review">
                    <span class="stat-number" id="reviewCount">{{ $reviewCount }}</span>
                    <span class="stat-label">Need review</span>
                </div>

                <div class="stat total">
                    <span class="stat-number" id="reviewedCount">{{ $reviewedCount }}</span>
                    <span class="stat-label">Reviewed</span>
                </div>

            </section>

            {{-- LEARNING PROGRESS --}}

            <div class="progress-heading">
                <span>YOUR LEARNING PROGRESS</span>

                <span id="progressPercentage">
                    {{ $set->flashcards()->count() ? round(($reviewedCount / $set->flashcards()->count()) * 100) : 0 }}%
                </span>
            </div>

            <div class="progress-track">
                <div
                    class="progress-fill"
                    id="progressFill"
                    style="width: {{ $set->flashcards()->count() ? ($reviewedCount / $set->flashcards()->count()) * 100 : 0 }}%"
                ></div>
            </div>

            @if ($reviewMode)
                <p class="card-label" style="text-align:center; margin-bottom:20px;">
                    CARDS THAT NEED MORE PRACTICE
                </p>
            @endif

            @if ($cards->count())

                @php
                    $cardData = $cards->map(fn ($c) => [
                        'id' => data_get($c, 'id'),
                        'front' => data_get($c, 'front', ''),
                        'back' => data_get($c, 'back', ''),
                        'known' => (bool) data_get($c, 'known', false),
                        'reviewed' => (bool) data_get($c, 'reviewed', false),
                        'markUrl' => route(
                            'sets.flashcards.mark',
                            [$set, data_get($c, 'id')]
                        ),
                    ])->values();
                @endphp

                <div class="card-area">

                    <div class="card-meta">
                        <span class="card-label">YOUR FLASHCARD COLLECTION</span>

                        <span class="counter" id="count">
                            1 / {{ $cards->count() }}
                        </span>
                    </div>

                    {{-- FLASHCARD --}}

                    <div
                        id="scene"
                        role="button"
                        tabindex="0"
                        aria-label="Reveal flashcard answer"
                        aria-keyshortcuts="Enter Space"
                    >
                        <div id="card">

                            <div class="face">
                                <span class="face-label">QUESTION ✧</span>
                                <span class="face-content" id="frontText"></span>
                            </div>

                            <div class="face back">
                                <span class="face-label">ANSWER ✿</span>
                                <span class="face-content" id="backText"></span>
                            </div>

                        </div>
                    </div>

                    <p class="flip-hint" id="flipHint">
                        Tap or select the card to reveal the answer.
                    </p>

                    {{-- RATE THE CARD --}}

                    <div class="rate-actions">

                        <button
                            type="button"
                            class="rate-button again"
                            id="againButton"
                            disabled
                        >
                            ↶ Study Again
                        </button>

                        <button
                            type="button"
                            class="rate-button known"
                            id="knownButton"
                            disabled
                        >
                            I Know This ✓
                        </button>

                    </div>

                    <p class="swipe-hint">
                        Swipe left for Study Again · Swipe right for I Know This
                    </p>

                    {{-- PREVIOUS AND NEXT --}}

                    <div class="controls">

                        <button
                            type="button"
                            class="nav-button"
                            id="prevButton"
                        >
                            <span class="arrow" aria-hidden="true">←</span>
                            Previous
                        </button>

                        <button
                            type="button"
                            class="nav-button"
                            id="nextButton"
                        >
                            Next
                            <span class="arrow" aria-hidden="true">→</span>
                        </button>

                    </div>

                    <p class="save-error" id="saveError" role="status" aria-live="polite"></p>

                </div>

            @else

                {{-- EMPTY STATE --}}

                <div class="empty-state">

                    <div class="symbol">✿</div>

                    @if ($reviewMode)

                        <h2>All caught up!</h2>

                        <p>
                            You have no reviewed cards waiting for another round.
                        </p>

                        <a class="review-link" href="{{ route('sets.flashcards', $set) }}">
                            Return to all flashcards
                        </a>

                    @else

                        <h2>Your study cards are waiting</h2>

                        <p>
                            Create your first set of flashcards from your uploaded study materials.
                        </p>

                    @endif

                </div>

            @endif

            {{-- REVIEW LINK --}}

            @if (!$reviewMode && $reviewCount > 0)

                <a
                    class="review-link"
                    href="{{ route('sets.flashcards', ['set' => $set->id, 'review' => 1]) }}"
                >
                    Review the {{ $reviewCount }} cards that need more practice →
                </a>

            @endif

            {{-- ADDITIONAL ACTIONS --}}

            <section class="action-section">

                <div class="action-heading">
                    <h2>Keep building your collection</h2>

                    <p>
                        Add your own questions or let Shelly prepare more cards.
                    </p>
                </div>

                <div class="flashcard-action-row">

                    <a
                        href="{{ route('sets.flashcards.manual', $set) }}"
                        class="action-card"
                    >

                        <span class="action-icon">＋</span>

                        <span class="action-text">
                            <span class="action-title">Add Manual Cards</span>

                            <span class="action-description">
                                Write your own questions and answers.
                            </span>
                        </span>

                        <span class="action-arrow">›</span>

                    </a>

                    <a
                        href="{{ route('sets.flashcards.setup', $set) }}"
                        class="action-card generate"
                    >

                        <span class="action-icon">✧</span>

                        <span class="action-text">
                            <span class="action-title">
                                {{ $set->flashcards()->count() ? 'Generation Settings' : 'Create Flashcards' }}
                            </span>

                            <span class="action-description">
                                {{ $set->flashcards()->count() ? 'Customize and generate more study cards.' : 'Let Shelly create cards from your materials.' }}
                            </span>
                        </span>

                        <span class="action-arrow">›</span>

                    </a>

                </div>

            </section>

        </section>

    </main>

    <footer class="site-footer">
        <span class="butterfly" aria-hidden="true"></span>
        <span>A gentle space to learn and grow.</span>
        <span class="ribbon" aria-hidden="true"></span>
    </footer>

</div>

{{-- AI GENERATION LOADING OVERLAY --}}

<div class="generation-overlay" id="generationOverlay">

    <div class="generation-content">

        <div class="generation-bow">୨୧</div>

        <h2>Creating Your Flashcards</h2>

        <p>
            Shelly is preparing your study cards.
            Just a little moment while everything comes together!
        </p>

        <div class="generation-track">
            <div class="generation-progress"></div>
        </div>

        <p>Made with care for your learning journey.</p>

    </div>

</div>

@if ($cards->count())

<script>
    const cards = @json($cardData);

    const logUrl = @json(route('study-log.store'));
    const studySetId = @json($set->id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let currentIndex = 0;
    let saving = false;
    let pointerStartX = null;
    let pointerStartY = null;
    let wasSwipe = false;

    let knownCount = {{ $knownCount }};
    let reviewCount = {{ $reviewCount }};
    let reviewedCount = {{ $reviewedCount }};

    const totalCards = {{ $set->flashcards()->count() }};

    const cardElement = document.getElementById('card');
    const scene = document.getElementById('scene');
    const againButton = document.getElementById('againButton');
    const knownButton = document.getElementById('knownButton');
    const errorElement = document.getElementById('saveError');

    /* FLASHCARD SOUND */

    let audioCtx = null;

    function tone(freq, start, duration, type) {
        try {
            audioCtx = audioCtx ||
                new (
                    window.AudioContext ||
                    window.webkitAudioContext
                )();

            const oscillator = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            oscillator.type = type;
            oscillator.frequency.value = freq;

            gain.gain.setValueAtTime(
                0.18,
                audioCtx.currentTime + start
            );

            gain.gain.exponentialRampToValueAtTime(
                0.001,
                audioCtx.currentTime + start + duration
            );

            oscillator.connect(gain);
            gain.connect(audioCtx.destination);

            oscillator.start(audioCtx.currentTime + start);

            oscillator.stop(
                audioCtx.currentTime + start + duration
            );

        } catch (error) {
            console.warn('Audio is unavailable:', error);
        }
    }

    function playDing() {
        tone(880, 0, 0.25, 'sine');
        tone(1320, 0.12, 0.4, 'sine');
    }

    /* FLASHCARD PROGRESS */

    function updateStats() {
        document.getElementById('knownCount').textContent = knownCount;
        document.getElementById('reviewCount').textContent = reviewCount;
        document.getElementById('reviewedCount').textContent = reviewedCount;

        const percentage = totalCards
            ? Math.round((reviewedCount / totalCards) * 100)
            : 0;

        document.getElementById('progressPercentage').textContent =
            percentage + '%';

        document.getElementById('progressFill').style.width =
            percentage + '%';
    }

    /* DISPLAY CURRENT FLASHCARD */

    function showCard() {
        const current = cards[currentIndex];

        cardElement.classList.remove('flipped');

        document.getElementById('frontText').textContent = current.front;
        document.getElementById('backText').textContent = current.back;

        document.getElementById('count').textContent =
            (currentIndex + 1) + ' / ' + cards.length;

        document.getElementById('prevButton').disabled =
            currentIndex === 0;

        document.getElementById('nextButton').disabled =
            currentIndex === cards.length - 1;

        againButton.disabled = true;
        knownButton.disabled = true;

        errorElement.textContent = '';
        errorElement.className = 'save-error';

        document.getElementById('flipHint').textContent =
            current.reviewed
                ? (current.known
                    ? 'You marked this card as known. Tap to view the answer.'
                    : 'You marked this card for more practice. Tap to view the answer.')
                : 'Tap or select the card to reveal the answer.';
    }

    /* FLIP FLASHCARD BY SELECTING THE CARD */

    function flipCard() {
        if (saving) return;

        if (cardElement.classList.contains('flipped')) {
            cardElement.classList.remove('flipped');

            againButton.disabled = true;
            knownButton.disabled = true;

            document.getElementById('flipHint').textContent =
                'Tap the card to reveal the answer.';

            return;
        }

        cardElement.classList.add('flipped');

        playDing();

        againButton.disabled = false;
        knownButton.disabled = false;

        document.getElementById('flipHint').textContent =
            'How well do you know this topic?';
    }

    /* MOVE BETWEEN FLASHCARDS */

    function moveCard(direction) {
        const nextIndex = currentIndex + direction;

        if (nextIndex < 0 || nextIndex >= cards.length || saving) {
            return;
        }

        currentIndex = nextIndex;

        showCard();
    }

    /* SAVE FLASHCARD PROGRESS */

    async function rateCard(known) {
        if (saving || !cardElement.classList.contains('flipped')) {
            return;
        }

        const current = cards[currentIndex];

        if (current.reviewed && current.known === known) {
            errorElement.className = 'save-error warning';

            errorElement.textContent = known
                ? 'This card is already saved as Known!'
                : 'This card is already saved under Need Review!';

            document.getElementById('flipHint').textContent = known
                ? 'This card is already marked as Known.'
                : 'This card is already marked for more practice.';

            return;
        }

        saving = true;

        againButton.disabled = true;
        knownButton.disabled = true;

        const wasReviewed = current.reviewed;
        const wasKnown = current.known;

        errorElement.className = 'save-error';
        errorElement.textContent = 'Saving your progress...';

        try {
            const response = await fetch(current.markUrl, {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },

                body: JSON.stringify({
                    known: known
                })
            });

            if (!response.ok) {
                throw new Error(
                    'Your learning progress could not be saved.'
                );
            }

            // Update counters only after the server confirms the save.
            if (wasReviewed) {
                if (wasKnown) {
                    knownCount--;
                } else {
                    reviewCount--;
                }
            } else {
                reviewedCount++;
            }

            if (known) {
                knownCount++;
            } else {
                reviewCount++;
            }

            current.known = known;
            current.reviewed = true;

            updateStats();

            errorElement.className = 'save-error success';

            errorElement.textContent = known
                ? 'Saved! This card is now marked as Known.'
                : 'Saved! This card is now marked for Need Review.';

            document.getElementById('flipHint').textContent = known
                ? 'This card is saved as Known.'
                : 'This card is saved for another round of practice.';

            // Record study activity only the first time this card is reviewed.
            if (!wasReviewed) {
                try {
                    const logResponse = await fetch(logUrl, {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },

                        body: JSON.stringify({
                            study_set_id: studySetId,
                            type: 'flashcard'
                        })
                    });

                    if (!logResponse.ok) {
                        console.warn('Points could not be logged.');
                    }

                } catch (logError) {
                    console.error(
                        'Points could not be logged:',
                        logError
                    );
                }
            }

            // Give the learner time to read the confirmation.
            if (currentIndex < cards.length - 1) {
                setTimeout(() => {
                    moveCard(1);
                }, 1200);
            }

        } catch (error) {
            console.error(error);

            errorElement.className = 'save-error';

            errorElement.textContent =
                'Could not save your progress. Please try again.';

            againButton.disabled = false;
            knownButton.disabled = false;

        } finally {
            saving = false;
        }
    }

    /* CARD CLICK AND KEYBOARD CONTROLS */

    scene.addEventListener('click', function() {
        if (wasSwipe) {
            wasSwipe = false;
            return;
        }

        flipCard();
    });

    scene.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            flipCard();
        }
    });

    /* SWIPE CONTROLS */

    scene.addEventListener('pointerdown', function(event) {
        pointerStartX = event.clientX;
        pointerStartY = event.clientY;
        wasSwipe = false;
    });

    scene.addEventListener('pointerup', function(event) {
        if (pointerStartX === null || pointerStartY === null) {
            return;
        }

        const differenceX = event.clientX - pointerStartX;
        const differenceY = event.clientY - pointerStartY;

        pointerStartX = null;
        pointerStartY = null;

        if (
            Math.abs(differenceX) < 75 ||
            Math.abs(differenceX) < Math.abs(differenceY) * 1.2
        ) {
            return;
        }

        if (!cardElement.classList.contains('flipped')) {
            return;
        }

        wasSwipe = true;

        if (differenceX > 0) {
            rateCard(true);
        } else {
            rateCard(false);
        }
    });

    scene.addEventListener('pointercancel', function() {
        pointerStartX = null;
        pointerStartY = null;
    });

    /* BUTTON EVENT LISTENERS */

    againButton.addEventListener('click', () => rateCard(false));
    knownButton.addEventListener('click', () => rateCard(true));

    document.getElementById('prevButton').addEventListener('click', () => {
        moveCard(-1);
    });

    document.getElementById('nextButton').addEventListener('click', () => {
        moveCard(1);
    });

    /* INITIALIZE FLASHCARDS */

    showCard();
    updateStats();

</script>

@endif

</body>
</html>
