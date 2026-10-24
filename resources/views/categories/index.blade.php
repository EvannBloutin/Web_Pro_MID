@extends('layouts.app')
@section('title', 'Categories')
@section('content')
<div class="row between">
    <h1>Categories</h1>
    <a class="btn" href="{{ route('categories.create') }}">+ New category</a>
</div>
<div class="card">
    <table>
        <thead><tr><th>Name</th><th>Description</th><th>Books</th><th></th></tr></thead>
        <tbody>
        @forelse ($categories as $category)
            <tr>
                <td><a href="{{ route('books.index', ['category' => $category->id]) }}">{{ $category->name }}</a></td>
                <td class="muted">{{ \Illuminate\Support\Str::limit($category->description, 70) }}</td>
                <td>{{ $category->books_count }}</td>
                <td class="actions">
                    <a class="btn btn-sm btn-light" href="{{ route('categories.edit', $category) }}">Edit</a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">No categories yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
@endsection
