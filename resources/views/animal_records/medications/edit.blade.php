@extends('layouts.app')

@section('content')
    <h2>{{ isset($medication) ? 'Edit' : 'Add' }} Medication</h2>

    <form action="{{ isset($medication) ? route('medications.update', $medication) : route('medications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($medication)) @method('PUT') @endif

        <div class="mb-3">
            <label>Animal</label>
            <select name="animal_id" class="form-control" required>
                @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}" {{ (isset($medication) && $medication->animal_id == $animal->id) ? 'selected' : '' }}>
                        {{ $animal->type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Medication Name</label>
            <input type="text" name="medicine_name" class="form-control" value="{{ old('medicine_name', $medication->medicine_name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Dosage</label>
            <input type="text" name="dosage" class="form-control" value="{{ old('dosage', $medication->dosage ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Administration Date</label>
            <input type="date" name="administration_date" class="form-control" value="{{ old('administration_date', $medication->administration_date ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Next Dose Date</label>
            <input type="date" name="next_dose_date" class="form-control" value="{{ old('next_dose_date', $medication->next_dose_date ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Instructions</label>
            <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $medication->instructions ?? '') }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_completed" id="is_completed" value="1"
                {{ old('is_completed', $medication->is_completed ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_completed">
                Treatment Completed
            </label>
        </div>

        <div class="mb-3">
            <label>Images (max 3)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        @if ($medication->media->count())
            <div class="mb-3">
                <label>Existing Images</label>
                <div class="d-flex flex-wrap">
                    @foreach ($medication->media as $media)
                        <div class="me-3 mb-2 text-center">
                            <img src="{{ asset('storage/' . $media->image_path) }}" width="100" class="mb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $media->id }}" id="delete_{{ $media->id }}">
                                <label class="form-check-label" for="delete_{{ $media->id }}">Delete</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <button class="btn btn-primary">Save</button>
    </form>
@endsection
