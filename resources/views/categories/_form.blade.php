@csrf
<label for="name">Name</label>
<input id="name" type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required>

<label for="description">Description</label>
<textarea id="description" name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>

<p class="row">
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-light" href="{{ route('categories.index') }}">Cancel</a>
</p>
