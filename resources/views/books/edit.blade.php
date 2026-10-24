@extends('layouts.app')
@section('title', 'Edit book')
@section('content')
<h1>Edit book</h1>
<div class="card">
    <form method="POST" action="{{ route('books.update', $book) }}">
        @method('PUT')
        @include('books._form')
    </form>
</div>
@endsection
