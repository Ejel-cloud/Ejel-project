@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Marketplace</h1>

    {{-- Filters --}}
    <form method="GET" action="{{ route('marketplace.index') }}" class="mb-4 border rounded p-3 bg-light">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label class="form-label">City</label>
                <select name="city" class="form-select">
                    <option value="">-- All --</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">Animal Type</label>
                <select name="animal_type" class="form-select">
                    <option value="">-- All --</option>
                    @foreach($animalTypes as $type)
                        <option value="{{ $type }}" {{ request('animal_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">-- All --</option>
                    <option value="animal" {{ request('category') == 'animal' ? 'selected' : '' }}>Animal</option>
                    <option value="feed" {{ request('category') == 'feed' ? 'selected' : '' }}>Feed</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">Min Price</label>
                <input type="number" name="price_min" value="{{ request('price_min') }}" class="form-control">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">Max Price</label>
                <input type="number" name="price_max" value="{{ request('price_max') }}" class="form-control">
            </div>
        </div>
        <div class="mt-2">
            <button class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('marketplace.index') }}" class="btn btn-secondary">❌ Clear</a>
        </div>
    </form>

    {{-- Listings --}}
    <div class="row">
        @forelse($listings as $listing)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($listing->animal && $listing->animal->media->first())
                        <img src="{{ asset($listing->animal->media->first()->file_path) }}"
                             class="card-img-top" alt="Animal">
                    @else
                        <div class="text-center text-muted p-5 bg-light">
                            No Image
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ ucfirst($listing->animal_type ?? $listing->animal->type) }}</h5>
                        <p class="text-muted mb-1">City: {{ $listing->city }}</p>
                        <p class="text-muted mb-1">Seller: {{ $listing->user->name }}</p>
                        <p class="h5 text-success">{{ number_format($listing->price) }} SYP</p>
                        <p class="card-text">{{ Str::limit($listing->description, 100) }}</p>
                        <a href="{{ route('marketplace.show', $listing->id) }}"
                           class="btn btn-outline-primary w-100 mt-2">
                            View Listing
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No listings found.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $listings->links() }}
    </div>
</div>
@endsection
