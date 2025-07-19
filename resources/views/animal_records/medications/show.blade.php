@extends('layouts.app')

@section('content')
    <h2>Medication Details</h2>

    <ul class="list-group">
        <li class="list-group-item"><strong>Animal:</strong> {{ $medication->animal->type ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Medication Name:</strong> {{ $medication->medicine_name }}</li>
        <li class="list-group-item"><strong>Dosage:</strong> {{ $medication->dosage }}</li>
        <li class="list-group-item"><strong>Administration Date:</strong> {{ $medication->administration_date }}</li>
        <li class="list-group-item"><strong>Next Dose Date:</strong> {{ $medication->next_dose_date ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Instructions:</strong> {{ $medication->instructions ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ $medication->is_completed ? 'Completed' : 'In Progress' }}</li>
        <li class="list-group-item">
            <strong>Images:</strong><br>
            @foreach ($medication->media as $media)
                <img src="{{ asset('storage/' . $media->image_path) }}" width="100" class="me-2 mb-2">
            @endforeach
        </li>
    </ul>

    <a href="{{ route('medications.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection
