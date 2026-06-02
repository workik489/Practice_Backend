<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User | {{ config('app.name', 'Laravel') }}</title>
    @include('users.partials.styles')
</head>
<body>
    <main>
        <header>
            <div>
                <h1>Add User</h1>
                <p>Create a new user record.</p>
            </div>
            <a href="{{ route('users.index') }}" class="button">Back to Users</a>
        </header>

        <section class="panel">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                @include('users.partials.form', ['user' => null, 'submitLabel' => 'Create User'])
            </form>
        </section>
    </main>
</body>
</html>
