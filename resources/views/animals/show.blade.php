@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Animal Details: {{ $animal->type }}</h2>

    {{-- Basic Info --}}
    <div class="card mb-4">
        <div class="card-header">Basic Information</div>
        <div class="card-body">
            <p><strong>Type:</strong> {{ $animal->type }}</p>
            <p><strong>Gender:</strong> {{ $animal->gender }}</p>
            <p><strong>Birth Date:</strong> {{ $animal->birth_date ?? '—' }}</p>
            <p><strong>Notes:</strong> {{ $animal->notes ?? '—' }}</p>
            <p><strong>Health Status:</strong> {{ $animal->health_status ?? '—' }}</p>
        </div>
    </div>

    {{-- Images --}}
    <div class="card mb-4">
        <div class="card-header">Images</div>
        <div class="card-body d-flex flex-wrap gap-3">
            @forelse ($animal->media as $image)
                <div>
                    <img src="{{ asset($image->file_path) }}" alt="Animal Image" width="150" class="img-thumbnail">
                </div>
            @empty
                <p>No images available.</p>
            @endforelse
        </div>
    </div>

    {{-- Related Records --}}
    <div class="card mb-4">
        <div class="card-header">Related Records</div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ route('animal-records.families.index') }}">Family Records</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('animal-records.diseases.index') }}">Disease Records</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('animal-records.medications.index') }}">Medicine Records</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('animal-records.vaccines.index') }}">Vaccine Records</a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Back Button --}}
    <a href="{{ route('animals.index') }}" class="btn btn-secondary">Back</a>

{{-- Sell Animal Button --}}
@if (auth()->id() === $animal->user_id)
    <form action="{{ route('marketplace.create') }}" method="GET" class="mt-3">
        <input type="hidden" name="animal_id" value="{{ $animal->id }}">
        <button type="submit" class="btn btn-success">🐪 List this Animal for Sale</button>
    </form>
@endif

</div>
@endsection
