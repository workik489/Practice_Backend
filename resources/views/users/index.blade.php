<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users | {{ config('app.name', 'Laravel') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #f6f7f9; color: #18181b; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        main { width: 100%; max-width: 1120px; margin: 0 auto; padding: 32px 24px; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 28px; }
        .title h1 { margin: 0; font-size: 28px; line-height: 1.2; letter-spacing: 0; }
        .title p { margin: 6px 0 0; color: #62646a; font-size: 14px; }
        .actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        a { color: inherit; }
        button, .button { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; border-radius: 6px; border: 1px solid #c9ccd3; background: #fff; color: #18181b; padding: 9px 14px; font-size: 14px; font-weight: 700; text-decoration: none; cursor: pointer; white-space: nowrap; }
        button:hover, .button:hover { background: #eef0f3; }
        .primary { border-color: #116149; background: #116149; color: #fff; }
        .primary:hover { background: #0d4f3b; }
        .danger { border-color: #dc2626; color: #b91c1c; }
        .danger:hover { background: #fef2f2; }
        .notice { margin-bottom: 18px; border: 1px solid #86efac; border-radius: 6px; background: #f0fdf4; color: #166534; padding: 12px 14px; font-size: 14px; font-weight: 700; }
        .datatable { border: 1px solid #dfe3e8; border-radius: 8px; background: #fff; overflow: hidden; }
        .toolbar { display: grid; gap: 14px; padding: 16px; border-bottom: 1px solid #e5e7eb; background: #fbfcfd; }
        .filters { display: grid; grid-template-columns: minmax(220px, 1fr) 140px auto auto; gap: 10px; align-items: end; }
        label { display: grid; gap: 6px; color: #52525b; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; }
        input, select { width: 100%; min-height: 38px; border: 1px solid #c9ccd3; border-radius: 6px; background: #fff; color: #18181b; padding: 8px 10px; font: inherit; font-size: 14px; }
        input:focus, select:focus { outline: 2px solid #a7f3d0; border-color: #116149; }
        .summary { color: #62646a; font-size: 14px; font-weight: 600; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 980px; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: middle; font-size: 14px; }
        th { background: #f9fafb; color: #52525b; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        th a { display: inline-flex; align-items: center; gap: 6px; color: inherit; text-decoration: none; }
        .sort-mark { color: #116149; font-size: 11px; }
        tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #fbfcfd; }
        .muted { color: #71717a; }
        .address { max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .row-actions { display: flex; gap: 8px; align-items: center; }
        .empty { border: 1px solid #dfe3e8; border-radius: 8px; background: #fff; padding: 40px 24px; text-align: center; }
        .empty h2 { margin: 0; font-size: 20px; }
        .empty p { margin: 8px 0 20px; color: #62646a; }
        .pagination { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 14px 16px; border-top: 1px solid #e5e7eb; background: #fbfcfd; }
        .page-links { display: flex; gap: 8px; align-items: center; }
        @media (max-width: 640px) {
            main { padding: 24px 16px; }
            header { align-items: flex-start; flex-direction: column; }
            .actions, .actions .button { width: 100%; }
            .filters { grid-template-columns: 1fr; }
            .pagination { align-items: stretch; flex-direction: column; }
            .page-links, .page-links .button { width: 100%; }
        }
    </style>
</head>
<body>
    <main>
        <header>
            <div class="title">
                <h1>Users</h1>
                <p>Manage user records from the web panel.</p>
            </div>
            <div class="actions">
                <a href="{{ route('home') }}" class="button">Home</a>
                <a href="{{ route('users.create') }}" class="button primary">Add User</a>
            </div>
        </header>

        @if (session('status'))
            <div class="notice">{{ session('status') }}</div>
        @endif

        <section class="datatable">
            <form method="GET" action="{{ route('users.index') }}" class="toolbar">
                <div class="filters">
                    <label>
                        Search
                        <input type="search" name="search" value="{{ $search }}" placeholder="Name, email, username, address">
                    </label>
                    <label>
                        Rows
                        <select name="per_page">
                            @foreach ([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </label>
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                    <button type="submit" class="primary">Apply</button>
                    <a href="{{ route('users.index') }}" class="button">Reset</a>
                </div>
                <div class="summary">
                    Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                </div>
            </form>

            @if ($users->count())
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                @foreach ([
                                    'name' => 'Name',
                                    'email' => 'Email',
                                    'username' => 'Username',
                                    'address' => 'Address',
                                    'created_at' => 'Created',
                                ] as $column => $label)
                                    @php
                                        $nextDirection = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <th>
                                        <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => $column, 'direction' => $nextDirection, 'page' => 1])) }}">
                                            {{ $label }}
                                            @if ($sort === $column)
                                                <span class="sort-mark">{{ $direction === 'asc' ? 'ASC' : 'DESC' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                @endforeach
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td><strong>{{ $user['name'] }}</strong></td>
                                    <td>{{ $user['email'] }}</td>
                                    <td class="muted">{{ $user['username'] ?: 'Not set' }}</td>
                                    <td class="muted address" title="{{ $user['address'] ?: 'Not set' }}">{{ $user['address'] ?: 'Not set' }}</td>
                                    <td class="muted">{{ $user['created_at'] }}</td>
                                    <td>
                                        <div class="row-actions">
                                            <a href="{{ route('users.edit', $user['id']) }}" class="button">Edit</a>
                                            <form method="POST" action="{{ route('users.destroy', $user['id']) }}" onsubmit="return confirm('Delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <span class="summary">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
                    <div class="page-links">
                        @if ($users->onFirstPage())
                            <span class="button muted">Previous</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="button">Previous</a>
                        @endif

                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="button">Next</a>
                        @else
                            <span class="button muted">Next</span>
                        @endif
                    </div>
                </div>
            @else
                <section class="empty">
                    <h2>No users found</h2>
                    <p>Try another search or create a new user.</p>
                    <a href="{{ route('users.create') }}" class="button primary">Add User</a>
                </section>
            @endif
        </section>
    </main>
</body>
</html>
