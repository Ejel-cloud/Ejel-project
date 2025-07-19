@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">My Animals</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('animals.create') }}" class="btn btn-success">➕ Add Animal</a>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary ms-2">🌐 Go to Marketplace</a>
    </div>

    @if($animals->isEmpty())
        <div class="alert alert-info">You don't have any animals yet.</div>
    @else
        <div class="row">
            @foreach($animals as $animal)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($animal->media->first())
                            <img src="{{ asset($animal->media->first()->file_path) }}"
                                 class="card-img-top" alt="Animal Image">
                        @else
                            <div class="p-4 text-center text-muted">No Image</div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ ucfirst($animal->type) }} ({{ $animal->gender }})</h5>
                            <p class="mb-1"><strong>Age:</strong> {{ $animal->age }}</p>
                            <p class="mb-1"><strong>Health:</strong> {{ $animal->health_status }}</p>
                            <p class="mb-1"><strong>City:</strong> {{ $animal->city }}</p>

                            <a href="{{ route('animals.show', $animal->id) }}" class="btn btn-primary btn-sm mt-2">Details</a>
                            <a href="{{ route('animals.edit', $animal->id) }}" class="btn btn-outline-secondary btn-sm mt-2">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ✅ pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $animals->links() }}
        </div>
    @endif
</div>
@endsection
