@extends('layouts.app')
@section('title', 'New category')
@section('content')
<h1>New category</h1>
<div class="card">
    <form method="POST" action="{{ route('categories.store') }}">
        @include('categories._form')
    </form>
</div>
@endsection
