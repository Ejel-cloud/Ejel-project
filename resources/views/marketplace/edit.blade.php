@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Listing</h1>

    {{-- Flash & Validation Errors --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> please fix the errors below:
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('marketplace.update', $listing->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $listing->title) }}"
                class="form-control @error('title') is-invalid @enderror"
                maxlength="150"
                required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Category --}}
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select
                id="category"
                name="category"
                class="form-select @error('category') is-invalid @enderror"
                required>
                <option value="animal" {{ old('category', $listing->category)=='animal' ? 'selected':'' }}>Animal</option>
                <option value="feed"   {{ old('category', $listing->category)=='feed'   ? 'selected':'' }}>Feed</option>
                <option value="equipment" {{ old('category', $listing->category)=='equipment' ? 'selected':'' }}>Equipment</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Animal Type --}}
        <div class="mb-3">
            <label for="animal_type" class="form-label">Animal Type</label>
            <input
                type="text"
                id="animal_type"
                name="animal_type"
                value="{{ old('animal_type', $listing->animal_type) }}"
                class="form-control @error('animal_type') is-invalid @enderror">
            @error('animal_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- City --}}
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input
                type="text"
                id="city"
                name="city"
                value="{{ old('city', $listing->city) }}"
                class="form-control @error('city') is-invalid @enderror"
                required>
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <label for="price" class="form-label">Price (SYP)</label>
            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price', $listing->price) }}"
                class="form-control @error('price') is-invalid @enderror"
                step="0.01" min="0"
                required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $listing->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('marketplace.show', $listing->id) }}" class="btn btn-secondary">Cancel</a>
            <form
                action="{{ route('marketplace.destroy', $listing->id) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this listing?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Listing</button>
            </form>
        </div>
    </form>
</div>
@endsection
