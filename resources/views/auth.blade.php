<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($mode) }} | {{ config('app.name', 'Laravel') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #09090b; color: #fff; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        main { min-height: 100vh; display: grid; place-items: center; padding: 40px 24px; }
        .panel { width: 100%; max-width: 420px; border: 1px solid rgba(255,255,255,.12); border-radius: 8px; background: #fff; color: #09090b; padding: 32px; box-shadow: 0 25px 50px rgba(0,0,0,.35); }
        .eyebrow { margin: 0; color: #047857; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        h1 { margin: 12px 0 0; font-size: 32px; line-height: 1.15; letter-spacing: 0; }
        .copy { margin: 12px 0 28px; color: #52525b; font-size: 15px; line-height: 1.6; }
        form { display: grid; gap: 16px; }
        label { display: grid; gap: 7px; color: #3f3f46; font-size: 14px; font-weight: 700; }
        input { width: 100%; border: 1px solid #d4d4d8; border-radius: 6px; padding: 11px 12px; font: inherit; }
        input:focus { border-color: #047857; outline: 2px solid #a7f3d0; }
        .check { display: flex; align-items: center; gap: 8px; color: #52525b; font-size: 14px; font-weight: 600; }
        .check input { width: auto; }
        button { width: 100%; border: 0; border-radius: 6px; background: #047857; color: #fff; padding: 13px 16px; font-size: 14px; font-weight: 700; cursor: pointer; }
        button:hover { background: #065f46; }
        .error { color: #b91c1c; font-size: 13px; font-weight: 600; }
        .divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; color: #71717a; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .divider::before, .divider::after { content: ""; height: 1px; flex: 1; background: #e4e4e7; }
        .github { display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; border-radius: 6px; background: #09090b; color: #fff; padding: 13px 16px; text-decoration: none; font-size: 14px; font-weight: 700; transition: background .15s ease; }
        .github:hover { background: #27272a; }
        .github svg { width: 20px; height: 20px; fill: currentColor; flex: 0 0 auto; }
        .switch { margin: 24px 0 0; text-align: center; color: #52525b; font-size: 14px; }
        .switch a { color: #09090b; font-weight: 700; }
    </style>
</head>
<body>
    <main>
        <section class="panel">
            <p class="eyebrow">{{ config('app.name', 'Practice Backend') }}</p>
            <h1>{{ $mode === 'signup' ? 'Create your account' : 'Login to continue' }}</h1>
            <p class="copy">
                {{ $mode === 'signup' ? 'Create an account with email and password, or use GitHub.' : 'Login with email and password, or use GitHub.' }}
            </p>

            <form method="POST" action="{{ $mode === 'signup' ? route('signup.store') : route('login.store') }}">
                @csrf

                @if ($mode === 'signup')
                    <label>
                        Name
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label>
                        Username
                        <input type="text" name="username" value="{{ old('username') }}">
                        @error('username')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                @endif

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required {{ $mode === 'login' ? 'autofocus' : '' }}>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </label>

                <label>
                    Password
                    <input type="password" name="password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </label>

                @if ($mode === 'signup')
                    <label>
                        Confirm Password
                        <input type="password" name="password_confirmation" required>
                    </label>
                @else
                    <label class="check">
                        <input type="checkbox" name="remember" value="1">
                        Remember me
                    </label>
                @endif

                <button type="submit">{{ $mode === 'signup' ? 'Create account' : 'Login' }}</button>
            </form>

            <div class="divider">or</div>

            <a href="{{ route('github.redirect') }}" class="github">
                <svg aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.1.79-.25.79-.56v-2.16c-3.2.7-3.88-1.36-3.88-1.36-.52-1.34-1.28-1.7-1.28-1.7-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.7 1.25 3.36.96.1-.75.4-1.25.73-1.54-2.56-.29-5.25-1.28-5.25-5.69 0-1.26.45-2.29 1.19-3.1-.12-.29-.52-1.47.11-3.06 0 0 .98-.31 3.18 1.18A11.1 11.1 0 0 1 12 5.98c.98 0 1.96.13 2.88.39 2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.77.11 3.06.74.81 1.19 1.84 1.19 3.1 0 4.42-2.7 5.39-5.26 5.68.41.36.78 1.06.78 2.14v3.18c0 .31.21.67.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/>
                </svg>
                Continue with GitHub
            </a>

            <p class="switch">
                @if ($mode === 'signup')
                    Already have access? <a href="{{ route('login') }}">Login</a>
                @else
                    New here? <a href="{{ route('signup') }}">Signup</a>
                @endif
            </p>
        </section>
    </main>
</body>
</html>
