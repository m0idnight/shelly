
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff7fa">

    <title>@yield('title', 'Shelly') · Shelly</title>

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

        a {
            color: inherit;
        }

        button,
        input,
        textarea,
        select {
            font: inherit;
        }

        .site-shell {
            width: min(1120px, calc(100% - 48px));
            margin: 0 auto;
        }

        /* SINGLE FLOATING HEADER */

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

        /* BRAND */

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

        /* BUNNY LOGO */

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

        /* NAVIGATION - NO SECOND CAPSULE */

        .main-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav-link {
            position: relative;

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

            transition:
                background .25s ease,
                color .25s ease,
                transform .25s ease;
        }

        /* REMOVE UNDERLINES */

        .nav-link::after {
            display: none;
        }

        .nav-link:hover {
            color: var(--pink-dark);
            background: var(--pink-soft);
        }

        .nav-link.active {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        /* MAIN CONTENT */

        .page-wrap {
            min-height: 65vh;
            padding: 42px 0 65px;
        }

        .page-kicker {
            margin-bottom: 10px;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        .page-title {
            margin: 10px 0;
            color: #694c5b;
            font-family: "Playfair Display", serif;
            font-size: clamp(30px, 5vw, 46px);
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -.8px;
        }

        .page-description {
            max-width: 650px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
        }

        /* UPLOADED MATERIALS */

        .uploaded-materials-section {
            margin-top: 25px;
            padding: 28px;
        }

        .uploaded-materials-section .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .uploaded-materials-section h2 {
            margin: 5px 0;
            color: #694c5b;
            font-family: "Playfair Display", serif;
            font-size: 25px;
        }

        .uploaded-materials-section .section-heading p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .uploaded-materials-list {
            display: grid;
            gap: 12px;
        }

        .uploaded-material-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 17px;
            border: 1px solid var(--line);
            border-radius: 15px;
            background: #fff8fa;
            transition: border-color .2s ease, transform .2s ease;
        }

        .uploaded-material-item:hover {
            border-color: #e7b4c7;
            transform: translateY(-2px);
        }

        .uploaded-material-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 13px;
            background: #fceaf0;
            color: var(--pink-dark);
            font-size: 18px;
        }

        .uploaded-material-info {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .uploaded-material-info strong {
            overflow-wrap: anywhere;
            color: #694c5b;
            font-size: 14px;
            font-weight: 800;
        }

        .uploaded-material-info span {
            color: var(--muted);
            font-size: 12px;
        }

        .material-status {
            flex-shrink: 0;
            padding: 6px 10px;
            border-radius: 20px;
            background: #fceaf0;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: 800;
        }

        /* BUTTONS */

        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            min-height: 46px;
            padding: 0 21px;
            border: 1px solid transparent;
            border-radius: 13px;
            background: var(--pink);
            color: white;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        .button:hover {
            background: var(--pink-dark);
            transform: translateY(-1px);
        }

        .button-secondary {
            border-color: var(--line);
            background: white;
            color: var(--pink-dark);
            box-shadow: none;
        }

        .button-secondary:hover {
            background: var(--pink-light);
            color: var(--pink-dark);
        }

        /* FORM ELEMENTS */

        input,
        textarea,
        select {
            max-width: 100%;
        }

        input:focus,
        textarea:focus,
        select:focus,
        button:focus-visible,
        a:focus-visible {
            outline: 3px solid #f3c5d6;
            outline-offset: 3px;
        }

        /* MESSAGES */

        .error-message {
            padding: 13px 16px;
            border: 1px solid #efc2ce;
            border-radius: 13px;
            background: #fff0f3;
            color: #a34e65;
            font-size: 14px;
        }

        /* BUTTERFLY DECORATION */

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

        /* RIBBON DECORATION */

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

        /* RESPONSIVE LAYOUT */

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

            .page-title {
                font-size: clamp(29px, 8vw, 38px);
            }

            .uploaded-materials-section {
                padding: 20px;
            }

            .uploaded-material-item {
                flex-wrap: wrap;
            }

            .material-status {
                margin-left: 58px;
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
        }

        @stack('styles')
    </style>
</head>

<body>
    <div class="site-shell">

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

                {{-- HOME --}}
                <a
                    href="{{ route('dashboard', [], false) }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    @if(request()->routeIs('dashboard')) aria-current="page" @endif
                >
                    Home
                </a>

                {{-- UPLOAD MATERIALS --}}
                <a
                    href="{{ route('sets.create', [], false) }}"
                    class="nav-link {{ request()->routeIs('sets.create') ? 'active' : '' }}"
                    @if(request()->routeIs('sets.create')) aria-current="page" @endif
                >
                    Upload materials
                </a>

                {{-- MY STUDIES --}}
                <a
                    href="{{ route('dashboard', [], false) }}#my-study-sets"
                    class="nav-link {{ request()->routeIs(
                        'sets.show',
                        'sets.edit',
                        'sets.update',
                        'sets.destroy',
                        'sets.summary*',
                        'sets.summarize',
                        'sets.flashcards*',
                        'sets.quiz*'
                    ) ? 'active' : '' }}"
                    @if(request()->routeIs(
                        'sets.show',
                        'sets.edit',
                        'sets.update',
                        'sets.destroy',
                        'sets.summary*',
                        'sets.summarize',
                        'sets.flashcards*',
                        'sets.quiz*'
                    )) aria-current="page" @endif
                >
                    My studies
                </a>

            </nav>

        </header>

        <main class="page-wrap">
            @yield('content')
        </main>

        <footer class="site-footer">
            <span class="butterfly" aria-hidden="true"></span>
            <span>A gentle space to learn and grow.</span>
            <span class="ribbon" aria-hidden="true"></span>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
