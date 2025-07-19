@extends('layouts.app')

@section('title', 'Family Record Details')

@section('content')
<div class="container">
    <h2 class="mb-4">Family Record Details</h2>

    @include('partials.alerts')

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Animal:</strong> {{ $family->animal->type ?? '-' }}</p>
            <p><strong>Relation Type:</strong> {{ $family->relation_type ?? '-' }}</p>
            <p><strong>Description:</strong> {{ $family->description ?? '-' }}</p>

            <div class="mt-4">
                <strong>Images:</strong><br>
                <div class="d-flex flex-wrap">
                    @forelse($family->media as $media)
                        <img src="{{ asset('storage/' . $media->file_path) }}"
                             alt="Image"
                             class="img-thumbnail me-2 mb-2"
                             style="width: 250px; height: auto;">
                    @empty
                        <p class="text-muted mt-2">No images available.</p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('animal-records.families.index') }}" class="btn btn-secondary mt-4">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection
