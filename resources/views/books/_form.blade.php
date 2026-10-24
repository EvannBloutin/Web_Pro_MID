@csrf
<label for="title">Title</label>
<input id="title" type="text" name="title" value="{{ old('title', $book->title ?? '') }}" required>

<label for="author">Author</label>
<input id="author" type="text" name="author" value="{{ old('author', $book->author ?? '') }}" required>

<label for="isbn">ISBN</label>
<input id="isbn" type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" required>

<label for="category_id">Category</label>
<select id="category_id" name="category_id" required>
    <option value="">-- choose --</option>
    @foreach ($categories as $category)
        <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id ?? '') == $category->id)>{{ $category->name }}</option>
    @endforeach
</select>

<div class="grid grid-4">
    <div>
        <label for="published_year">Published year</label>
        <input id="published_year" type="number" name="published_year" value="{{ old('published_year', $book->published_year ?? '') }}">
    </div>
    <div>
        <label for="copies_total">Total copies</label>
        <input id="copies_total" type="number" min="1" name="copies_total" value="{{ old('copies_total', $book->copies_total ?? 1) }}" required>
    </div>
</div>

<label for="description">Description</label>
<textarea id="description" name="description" rows="4">{{ old('description', $book->description ?? '') }}</textarea>

<p class="row">
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-light" href="{{ route('books.index') }}">Cancel</a>
</p>
