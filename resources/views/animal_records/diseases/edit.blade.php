@extends('layouts.app')

@section('content')
    <h2>Edit Disease Record</h2>

    <form action="{{ route('animal-records.diseases.update', $disease) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="animal_id">Animal</label>
            <select name="animal_id" id="animal_id" class="form-control" required>
                @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}" {{ old('animal_id', $disease->animal_id) == $animal->id ? 'selected' : '' }}>
                        {{ $animal->type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name">Disease Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $disease->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="symptoms">Symptoms</label>
            <textarea name="symptoms" id="symptoms" class="form-control">{{ old('symptoms', $disease->symptoms) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="treatment">Treatment</label>
            <textarea name="treatment" id="treatment" class="form-control">{{ old('treatment', $disease->treatment) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="diagnosed_at">Diagnosis Date</label>
            <input type="date" name="diagnosed_at" id="diagnosed_at" class="form-control" value="{{ old('diagnosed_at', optional($disease->diagnosed_at)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label for="recovery_date">Recovery Date</label>
            <input type="date" name="recovery_date" id="recovery_date" class="form-control" value="{{ old('recovery_date', optional($disease->recovery_date)->format('Y-m-d')) }}">
        </div>

        <
