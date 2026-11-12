
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

        .focus-shell {
            width: min(1120px, calc(100% - 48px));
            min-height: 100vh;
            margin: 0 auto;
        }

        .focus-content {
            min-height: 100vh;
            padding: 35px 0 55px;
        }

        /* TYPOGRAPHY */

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

        /* RESPONSIVE */

        @media (max-width: 760px) {
            .focus-shell {
                width: calc(100% - 30px);
            }

            .focus-content {
                padding: 25px 0 40px;
            }

            .page-title {
                font-size: clamp(29px, 8vw, 38px);
            }
        }

        @media (max-width: 380px) {
            .focus-shell {
                width: calc(100% - 24px);
            }
        }

        @stack('styles')
    </style>
</head>

<body>
    <div class="focus-shell">

        <main class="focus-content">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
