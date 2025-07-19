@extends('layouts.app')

@section('content')
    <h2>Vaccine Details</h2>

    <ul class="list-group">
        <li class="list-group-item">
            <strong>Animal:</strong> {{ $vaccine->animal->type ?? 'N/A' }}
        </li>
        <li class="list-group-item">
            <strong>Vaccine Name:</strong> {{ $vaccine->name }}
        </li>
        <li class="list-group-item">
            <strong>Vaccine Type:</strong> {{ $vaccine->vaccine_type }}
        </li>
        <li class="list-group-item">
            <strong>Vaccination Date:</strong> {{ $vaccine->vaccination_date }}
        </li>
        <li class="list-group-item">
            <strong>Next Vaccination Date:</strong> {{ $vaccine->next_vaccination_date ?? '-' }}
        </li>
        <li class="list-group-item">
            <strong>Veterinarian:</strong> {{ $vaccine->veterinarian ?? '-' }}
        </li>
        <li class="list-group-item">
            <strong>Notes:</strong><br>
            {{ $vaccine->notes ?? '-' }}
        </li>
        <li class="list-group-item">
            <strong>Images:</strong><br>
            @if ($vaccine->media->count())
                @foreach ($vaccine->media as $media)
                    <img src="{{ asset('storage/' . $media->file_path) }}" width="100" class="me-2 mb-2 rounded border">
                @endforeach
            @else
                <p>No images uploaded.</p>
            @endif
        </li>
    </ul>

    <a href="{{ route('animal-records.vaccines.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection
