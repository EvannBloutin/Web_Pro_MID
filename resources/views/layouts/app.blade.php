<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Campus Library') - Campus Library</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<nav class="nav">
    <a class="brand" href="{{ route('dashboard') }}">📚 Campus Library</a>
    @auth
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('books.index') }}">Books</a>
        <a href="{{ route('loans.index') }}">{{ auth()->user()->is_admin ? 'All loans' : 'My loans' }}</a>
        @if (auth()->user()->is_admin)
            <a href="{{ route('categories.index') }}">Categories</a>
        @endif
        <span class="spacer"></span>
        <a href="{{ route('profile.edit') }}">{{ auth()->user()->name }}{{ auth()->user()->is_admin ? ' (admin)' : '' }}</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-light" type="submit">Logout</button>
        </form>
    @else
        <span class="spacer"></span>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @endauth
</nav>

<main class="container">
    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-err">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
