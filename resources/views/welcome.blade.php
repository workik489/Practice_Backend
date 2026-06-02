<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #fafafa; color: #09090b; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        main { width: 100%; max-width: 1040px; min-height: 100vh; margin: 0 auto; padding: 32px 24px; display: flex; flex-direction: column; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid #e4e4e7; }
        .brand { color: inherit; font-size: 18px; font-weight: 700; text-decoration: none; }
        button, .button { border-radius: 6px; border: 1px solid #d4d4d8; background: #fff; color: #09090b; padding: 10px 16px; font-size: 14px; font-weight: 700; text-decoration: none; cursor: pointer; transition: background .15s ease; }
        button:hover, .button:hover { background: #f4f4f5; }
        .primary { border-color: #09090b; background: #09090b; color: #fff; }
        .primary:hover { background: #27272a; }
        .content { flex: 1; display: grid; align-items: center; gap: 32px; padding: 48px 0; }
        .eyebrow { margin: 0; color: #047857; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        h1 { max-width: 720px; margin: 12px 0 0; font-size: clamp(36px, 6vw, 56px); line-height: 1.05; letter-spacing: 0; }
        .copy { max-width: 640px; margin: 18px 0 0; color: #52525b; font-size: 16px; line-height: 1.7; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px; }
        aside { border: 1px solid #e4e4e7; border-radius: 8px; background: #fff; padding: 20px; box-shadow: 0 1px 2px rgba(0,0,0,.05); }
        img { width: 80px; height: 80px; border-radius: 999px; border: 1px solid #e4e4e7; object-fit: cover; }
        dl { margin: 20px 0 0; display: grid; gap: 16px; font-size: 14px; }
        dt { color: #71717a; font-weight: 700; }
        dd { margin: 4px 0 0; overflow-wrap: anywhere; font-weight: 700; }
        @media (min-width: 760px) {
            .content { grid-template-columns: minmax(0, 1fr) 320px; }
        }
    </style>
</head>
<body>
    <main>
        <header>
            <a href="{{ route('home') }}" class="brand">{{ config('app.name', 'Practice Backend') }}</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </header>

        <section class="content">
            <div>
                <p class="eyebrow">GitHub authenticated</p>
                <h1>Welcome, {{ Auth::user()->name }}.</h1>
                <p class="copy">
                    This page and the test routes are protected by Laravel's session auth. Only users who complete GitHub login can access them.
                </p>
                <div class="actions">
                    <a href="{{ route('users.index') }}" class="button primary">Users</a>
                    <a href="/mongo-test" class="button">Mongo test</a>
                    <a href="/test-telescope" class="button">Telescope test</a>
                </div>
            </div>

            <aside>
                @if (Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                @endif
                <dl>
                    <div>
                        <dt>Name</dt>
                        <dd>{{ Auth::user()->name }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>{{ Auth::user()->email }}</dd>
                    </div>
                    <div>
                        <dt>Provider</dt>
                        <dd>{{ ucfirst(Auth::user()->provider) }}</dd>
                    </div>
                </dl>
            </aside>
        </section>
    </main>
</body>
</html>
