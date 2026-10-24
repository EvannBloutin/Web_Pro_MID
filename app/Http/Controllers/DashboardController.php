<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'books' => Book::count(),
            'categories' => Category::count(),
            'my_active' => $user->loans()->whereNull('returned_at')->count(),
            'my_overdue' => $user->loans()->whereNull('returned_at')->whereDate('due_at', '<', today())->count(),
        ];

        if ($user->is_admin) {
            $stats['members'] = User::count();
            $stats['active_loans'] = Loan::whereNull('returned_at')->count();
            $stats['overdue_loans'] = Loan::whereNull('returned_at')->whereDate('due_at', '<', today())->count();
        }

        $popular = Book::withCount('loans')->orderByDesc('loans_count')->take(5)->get();
        $latest = Book::with('category')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'popular', 'latest'));
    }
}
