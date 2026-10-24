@extends('layouts.app')
@section('title', 'Add a book')
@section('content')
<h1>Add a book</h1>
<div class="card">
    <form method="POST" action="{{ route('books.store') }}">
        @include('books._form')
    </form>
</div>
@endsection
