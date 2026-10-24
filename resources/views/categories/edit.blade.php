@extends('layouts.app')
@section('title', 'Edit category')
@section('content')
<h1>Edit category</h1>
<div class="card">
    <form method="POST" action="{{ route('categories.update', $category) }}">
        @method('PUT')
        @include('categories._form')
    </form>
</div>
@endsection
