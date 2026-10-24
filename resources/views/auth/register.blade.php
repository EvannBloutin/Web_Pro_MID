@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="card auth">
    <h1>Create an account</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>

        <label for="password">Password (min. 8 characters)</label>
        <input id="password" type="password" name="password" required>

        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>

        <p><button class="btn" type="submit">Register</button></p>
    </form>
    <p class="muted">Already registered? <a href="{{ route('login') }}">Login</a></p>
</div>
@endsection
