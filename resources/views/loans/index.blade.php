@extends('layouts.app')
@section('title', 'Loans')
@section('content')
<h1>{{ auth()->user()->is_admin ? 'All loans' : 'My loans' }}</h1>

<div class="row" style="margin-bottom:1rem">
    <a class="btn btn-sm {{ request('status') ? 'btn-light' : '' }}" href="{{ route('loans.index') }}">All</a>
    <a class="btn btn-sm {{ request('status') === 'active' ? '' : 'btn-light' }}" href="{{ route('loans.index', ['status' => 'active']) }}">Active</a>
    <a class="btn btn-sm {{ request('status') === 'overdue' ? '' : 'btn-light' }}" href="{{ route('loans.index', ['status' => 'overdue']) }}">Overdue</a>
    <a class="btn btn-sm {{ request('status') === 'returned' ? '' : 'btn-light' }}" href="{{ route('loans.index', ['status' => 'returned']) }}">Returned</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Book</th>
                @if (auth()->user()->is_admin)<th>Member</th>@endif
                <th>Borrowed</th><th>Due</th><th>Status</th><th></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($loans as $loan)
            <tr>
                <td><a href="{{ route('books.show', $loan->book) }}">{{ $loan->book->title }}</a></td>
                @if (auth()->user()->is_admin)<td>{{ $loan->user->name }}</td>@endif
                <td>{{ $loan->borrowed_at->format('d M Y') }}</td>
                <td>{{ $loan->due_at->format('d M Y') }}</td>
                <td>
                    @if ($loan->returned_at)
                        <span class="badge b-ok">Returned {{ $loan->returned_at->format('d M') }}</span>
                    @elseif ($loan->isOverdue())
                        <span class="badge b-bad">Overdue</span>
                    @else
                        <span class="badge b-warn">On loan</span>
                    @endif
                </td>
                <td>
                    @if (! $loan->returned_at)
                        <form method="POST" action="{{ route('loans.return', $loan) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm" type="submit">Return</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">No loans to display.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $loans->links() }}
</div>
@endsection
