@extends('layouts.app')
@section('title', $book->title)
@section('content')
<div class="row between">
    <h1>{{ $book->title }}</h1>
    @if (auth()->user()->is_admin)
        <div class="actions row">
            <a class="btn btn-light" href="{{ route('books.edit', $book) }}">Edit</a>
            <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Delete this book and its loans/reviews?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        </div>
    @endif
</div>

<div class="card">
    <p><strong>Author:</strong> {{ $book->author }}</p>
    <p><strong>Category:</strong> <a href="{{ route('books.index', ['category' => $book->category_id]) }}">{{ $book->category->name }}</a></p>
    <p><strong>ISBN:</strong> {{ $book->isbn }} @if ($book->published_year) · <strong>Year:</strong> {{ $book->published_year }}@endif</p>
    <p><strong>Copies:</strong> {{ $book->copies_available }} available / {{ $book->copies_total }} total</p>
    @if ($book->description)<p>{{ $book->description }}</p>@endif

    @if ($activeLoan)
        <div class="row">
            <span class="badge b-warn">You are borrowing this book (due {{ $activeLoan->due_at->format('d M Y') }})</span>
            <form method="POST" action="{{ route('loans.return', $activeLoan) }}">
                @csrf @method('PATCH')
                <button class="btn btn-sm" type="submit">Return it</button>
            </form>
        </div>
    @elseif ($book->copies_available > 0)
        <form method="POST" action="{{ route('loans.store', $book) }}">
            @csrf
            <button class="btn" type="submit">Borrow (14 days)</button>
        </form>
    @else
        <span class="badge b-bad">No copy available</span>
    @endif
</div>

<div class="card">
    <h2>Reviews ({{ $book->reviews->count() }})</h2>

    <form method="POST" action="{{ route('reviews.store', $book) }}">
        @csrf
        <div class="grid grid-4">
            <div>
                <label for="rating">Your rating</label>
                <select id="rating" name="rating" required>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected(old('rating', $myReview->rating ?? 5) == $i)>{{ $i }} ★</option>
                    @endfor
                </select>
            </div>
        </div>
        <label for="comment">Comment</label>
        <textarea id="comment" name="comment" rows="2">{{ old('comment', $myReview->comment ?? '') }}</textarea>
        <p><button class="btn btn-sm" type="submit">{{ $myReview ? 'Update my review' : 'Post review' }}</button></p>
    </form>

    @foreach ($book->reviews as $review)
        <div class="review">
            <div class="row between">
                <div>
                    <strong>{{ $review->user->name }}</strong>
                    <span class="stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    <span class="muted">{{ $review->created_at->diffForHumans() }}</span>
                </div>
                @if (auth()->user()->is_admin || $review->user_id === auth()->id())
                    <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>
                @endif
            </div>
            @if ($review->comment)<p>{{ $review->comment }}</p>@endif
        </div>
    @endforeach
</div>

<a href="{{ route('books.index') }}">&larr; Back to the catalogue</a>
@endsection
