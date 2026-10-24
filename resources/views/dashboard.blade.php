@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h1>Hello, {{ auth()->user()->name }} 👋</h1>

<div class="grid grid-4">
    <div class="card stat"><div class="n">{{ $stats['books'] }}</div><div class="l">Books in catalogue</div></div>
    <div class="card stat"><div class="n">{{ $stats['categories'] }}</div><div class="l">Categories</div></div>
    <div class="card stat"><div class="n">{{ $stats['my_active'] }}</div><div class="l">My current loans</div></div>
    <div class="card stat"><div class="n">{{ $stats['my_overdue'] }}</div><div class="l">My overdue loans</div></div>
    @if (auth()->user()->is_admin)
        <div class="card stat"><div class="n">{{ $stats['members'] }}</div><div class="l">Members</div></div>
        <div class="card stat"><div class="n">{{ $stats['active_loans'] }}</div><div class="l">Active loans (all)</div></div>
        <div class="card stat"><div class="n">{{ $stats['overdue_loans'] }}</div><div class="l">Overdue loans (all)</div></div>
    @endif
</div>

<div class="grid grid-3">
    <div class="card">
        <h2>Most borrowed books</h2>
        @forelse ($popular as $book)
            <div class="row between">
                <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                <span class="muted">{{ $book->loans_count }} loan(s)</span>
            </div>
        @empty
            <p class="muted">No books yet.</p>
        @endforelse
    </div>
    <div class="card">
        <h2>Recently added</h2>
        @forelse ($latest as $book)
            <div class="row between">
                <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                <span class="badge b-grey">{{ $book->category->name }}</span>
            </div>
        @empty
            <p class="muted">No books yet.</p>
        @endforelse
    </div>
</div>
@endsection
