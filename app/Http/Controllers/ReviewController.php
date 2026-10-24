<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /** Create or update the current user's review of a book. */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'book_id' => $book->id],
            $data
        );

        return redirect()->route('books.show', $book)->with('status', 'Your review has been saved.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        abort_unless($request->user()->is_admin || $review->user_id === $request->user()->id, 403);

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)->with('status', 'Review deleted.');
    }
}
