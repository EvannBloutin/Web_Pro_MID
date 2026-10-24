<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('category')
            ->withAvg('reviews', 'rating')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->q.'%';
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', $term)
                        ->orWhere('author', 'like', $term)
                        ->orWhere('isbn', 'like', $term);
                });
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->boolean('available'), fn ($q) => $q->where('copies_available', '>', 0))
            ->orderBy('title')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load(['category', 'reviews.user']);

        $activeLoan = auth()->user()->loans()
            ->where('book_id', $book->id)
            ->whereNull('returned_at')
            ->first();

        $myReview = $book->reviews->firstWhere('user_id', auth()->id());

        return view('books.show', compact('book', 'activeLoan', 'myReview'));
    }

    public function create()
    {
        return view('books.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $data['copies_available'] = $data['copies_total'];

        $book = Book::create($data);

        return redirect()->route('books.show', $book)->with('status', 'Book added to the catalogue.');
    }

    public function edit(Book $book)
    {
        return view('books.edit', [
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate($this->rules($book));

        // Keep the number of available copies consistent with the new total.
        $borrowed = $book->copies_total - $book->copies_available;

        if ($data['copies_total'] < $borrowed) {
            return back()->withInput()->withErrors([
                'copies_total' => "At least {$borrowed} copies are currently on loan.",
            ]);
        }

        $data['copies_available'] = $data['copies_total'] - $borrowed;
        $book->update($data);

        return redirect()->route('books.show', $book)->with('status', 'Book updated.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('books.index')->with('status', 'Book deleted.');
    }

    private function rules(?Book $book = null): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($book?->id)],
            'description' => ['nullable', 'string', 'max:3000'],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:'.date('Y')],
            'copies_total' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
