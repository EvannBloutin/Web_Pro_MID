@extends('layouts.app')
@section('title', 'Books')
@section('content')
<div class="row between">
    <h1>Books</h1>
    @if (auth()->user()->is_admin)
        <a class="btn" href="{{ route('books.create') }}">+ Add a book</a>
    @endif
</div>

<form class="card filters" method="GET" action="{{ route('books.index') }}">
    <div>
        <label for="q">Search (title, author, ISBN)</label>
        <input id="q" type="text" name="q" value="{{ request('q') }}">
    </div>
    <div>
        <label for="category">Category</label>
        <select id="category" name="category">
            <option value="">All</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="inline-check"><input type="checkbox" name="available" value="1" @checked(request()->boolean('available'))> Available only</label>
    </div>
    <div class="row">
        <button class="btn" type="submit">Filter</button>
        <a class="btn btn-light" href="{{ route('books.index') }}">Reset</a>
    </div>
</form>

<div class="grid grid-3">
    @forelse ($books as $book)
        <div class="card">
            <div class="row between">
                <span class="badge b-grey">{{ $book->category->name }}</span>
                @if ($book->copies_available > 0)
                    <span class="badge b-ok">{{ $book->copies_available }}/{{ $book->copies_total }} available</span>
                @else
                    <span class="badge b-bad">Unavailable</span>
                @endif
            </div>
            <h2 style="margin-top:.6rem"><a href="{{ route('books.show', $book) }}">{{ $book->title }}</a></h2>
            <p class="muted">{{ $book->author }}@if ($book->published_year) · {{ $book->published_year }}@endif</p>
            @if ($book->reviews_avg_rating)
                <span class="stars">{{ str_repeat('★', (int) round($book->reviews_avg_rating)) }}{{ str_repeat('☆', 5 - (int) round($book->reviews_avg_rating)) }}</span>
                <span class="muted">{{ number_format($book->reviews_avg_rating, 1) }}</span>
            @endif
        </div>
    @empty
        <p class="muted">No book matches your search.</p>
    @endforelse
</div>

{{ $books->links() }}
@endsection
