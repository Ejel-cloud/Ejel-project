@extends('layouts.app')

@section('content')
    <h2>Edit Vaccine Record</h2>

    <form action="{{ route('animal-records.vaccines.update', $vaccine) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Animal</label>
            <select name="animal_id" class="form-control" required>
                @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}" {{ $vaccine->animal_id == $animal->id ? 'selected' : '' }}>
                        {{ $animal->type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Vaccine Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $vaccine->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Vaccine Type</label>
            <input type="text" name="vaccine_type" class="form-control" value="{{ old('vaccine_type', $vaccine->vaccine_type) }}" required>
        </div>

        <div class="mb-3">
            <label>Vaccination Date</label>
            <input type="date" name="vaccination_date" class="form-control" value="{{ old('vaccination_date', $vaccine->vaccination_date) }}" required>
        </div>

        <div class="mb-3">
            <label>Next Vaccination Date</label>
            <input type="date" name="next_vaccination_date" class="form-control" value="{{ old('next_vaccination_date', $vaccine->next_vaccination_date) }}">
        </div>

        <div class="mb-3">
            <label>Veterinarian</label>
            <input type="text" name="veterinarian" class="form-control" value="{{ old('veterinarian', $vaccine->veterinarian) }}">
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control">{{ old('notes', $vaccine->notes) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Current Images:</label><br>
            @foreach ($vaccine->media as $media)
                <img src="{{ asset('storage/' . $media->file_path) }}" width="100" class="me-2 mb-2">
            @endforeach
        </div>

        <div class="mb-3">
            <label>Upload New Images (max 3)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
@endsection
