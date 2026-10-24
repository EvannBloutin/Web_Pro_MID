<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    private const MAX_ACTIVE_LOANS = 3;
    private const LOAN_DAYS = 14;

    /** Loan history: members see their own loans, admins see all loans. */
    public function index(Request $request)
    {
        $user = $request->user();

        $loans = Loan::with(['book', 'user'])
            ->when(! $user->is_admin, fn ($q) => $q->where('user_id', $user->id))
            ->when($request->query('status') === 'active', fn ($q) => $q->whereNull('returned_at'))
            ->when($request->query('status') === 'overdue', fn ($q) => $q->whereNull('returned_at')->whereDate('due_at', '<', today()))
            ->when($request->query('status') === 'returned', fn ($q) => $q->whereNotNull('returned_at'))
            ->latest('borrowed_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        $user = $request->user();

        $error = DB::transaction(function () use ($user, $book) {
            $book = Book::lockForUpdate()->find($book->id);

            if ($book->copies_available < 1) {
                return 'No copy of this book is available right now.';
            }

            if ($user->loans()->where('book_id', $book->id)->whereNull('returned_at')->exists()) {
                return 'You are already borrowing this book.';
            }

            if ($user->loans()->whereNull('returned_at')->count() >= self::MAX_ACTIVE_LOANS) {
                return 'You cannot borrow more than '.self::MAX_ACTIVE_LOANS.' books at the same time.';
            }

            Loan::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => today(),
                'due_at' => today()->addDays(self::LOAN_DAYS),
            ]);

            $book->decrement('copies_available');

            return null;
        });

        if ($error) {
            return back()->withErrors(['loan' => $error]);
        }

        return redirect()->route('loans.index')->with('status', 'Book borrowed. Please return it within '.self::LOAN_DAYS.' days.');
    }

    public function giveBack(Request $request, Loan $loan): RedirectResponse
    {
        abort_unless($request->user()->is_admin || $loan->user_id === $request->user()->id, 403);

        if ($loan->returned_at !== null) {
            return back()->withErrors(['loan' => 'This book has already been returned.']);
        }

        DB::transaction(function () use ($loan) {
            $loan->update(['returned_at' => today()]);
            $loan->book()->increment('copies_available');
        });

        return back()->with('status', 'Book returned. Thank you!');
    }
}
