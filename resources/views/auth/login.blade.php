@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="card auth">
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <label class="inline-check"><input type="checkbox" name="remember" value="1"> Remember me</label>

        <p><button class="btn" type="submit">Login</button></p>
    </form>
    <p class="muted">No account yet? <a href="{{ route('register') }}">Register</a></p>
</div>
@endsection
