<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User | {{ config('app.name', 'Laravel') }}</title>
    @include('users.partials.styles')
</head>
<body>
    <main>
        <header>
            <div>
                <h1>Edit User</h1>
                <p>Update {{ $user->name }}'s profile details.</p>
            </div>
            <a href="{{ route('users.index') }}" class="button">Back to Users</a>
        </header>

        <section class="panel">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                @include('users.partials.form', ['user' => $user, 'submitLabel' => 'Update User'])
            </form>
        </section>
    </main>
</body>
</html>
