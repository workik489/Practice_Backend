@php
    $editing = $user !== null;
@endphp

<div class="grid">
    <label>
        Name
        <input type="text" name="name" value="{{ old('name', $user?->name) }}" required>
        @error('name')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>

    <label>
        Email
        <input type="email" name="email" value="{{ old('email', $user?->email) }}" required>
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>

    <label>
        Username
        <input type="text" name="username" value="{{ old('username', $user?->username) }}">
        @error('username')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>

    <label>
        Password
        <input type="password" name="password" {{ $editing ? '' : 'required' }}>
        @if ($editing)
            <span class="hint">Leave blank to keep the current password.</span>
        @endif
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>

    <label>
        Address
        <textarea name="address">{{ old('address', $user?->address) }}</textarea>
        @error('address')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>
</div>

<div class="actions">
    <a href="{{ route('users.index') }}" class="button">Cancel</a>
    <button type="submit" class="primary">{{ $submitLabel }}</button>
</div>
