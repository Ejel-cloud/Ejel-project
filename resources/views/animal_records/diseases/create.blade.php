@extends('layouts.app')

@section('content')
    <h2>Add Disease Record</h2>

    <form action="{{ route('animal-records.diseases.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="animal_id">Animal</label>
            <select name="animal_id" id="animal_id" class="form-control" required>
                @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}" {{ old('animal_id') == $animal->id ? 'selected' : '' }}>
                        {{ $animal->type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name">Disease Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="symptoms">Symptoms</label>
            <textarea name="symptoms" id="symptoms" class="form-control">{{ old('symptoms') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="treatment">Treatment</label>
            <textarea name="treatment" id="treatment" class="form-control">{{ old('treatment') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="diagnosed_at">Diagnosis Date</label>
            <input type="date" name="diagnosed_at" id="diagnosed_at" class="form-control" value="{{ old('diagnosed_at') }}">
        </div>

        <div class="mb-3">
            <label for="recovery_date">Recovery Date</label>
            <input type="date" name="recovery_date" id="recovery_date" class="form-control" value="{{ old('recovery_date') }}">
        </div>

        <div class="mb-3">
            <label for="veterinarian">Veterinarian</label>
            <input type="text" name="veterinarian" id="veterinarian" class="form-control" value="{{ old('veterinarian') }}">
        </div>

        <div class="mb-3">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="images">Images (max 3)</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
@endsection
