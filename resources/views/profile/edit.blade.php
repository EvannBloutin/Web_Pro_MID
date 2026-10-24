@extends('layouts.app')
@section('title', 'My profile')
@section('content')
<h1>My profile</h1>
<div class="card" style="max-width:520px">
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PUT')
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>

        <h2 style="margin-top:1.5rem">Change password <span class="muted" style="font-weight:400">(optional)</span></h2>
        <label for="current_password">Current password</label>
        <input id="current_password" type="password" name="current_password" autocomplete="current-password">

        <label for="password">New password</label>
        <input id="password" type="password" name="password" autocomplete="new-password">

        <label for="password_confirmation">Confirm new password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">

        <p><button class="btn" type="submit">Save changes</button></p>
    </form>
</div>
@endsection
