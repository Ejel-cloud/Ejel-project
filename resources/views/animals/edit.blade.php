@extends('layouts.app')

@section('content')
<h2>Edit Animal</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('animals.update', $animal->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Type</label>
        <input type="text" name="type" value="{{ old('type', $animal->type) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="health_status" class="form-label">🩺 Health Status</label>
        <select name="health_status" id="health_status" class="form-select" required>
            <option value="healthy" {{ $animal->health_status == 'healthy' ? 'selected' : '' }}>Healthy</option>
            <option value="sick" {{ $animal->health_status == 'sick' ? 'selected' : '' }}>Sick</option>
            <option value="under_treatment" {{ $animal->health_status == 'under_treatment' ? 'selected' : '' }}>Under Treatment</option>
        </select>
    </div>


    <div class="mb-3">
        <label>Gender</label>
        <select name="gender" class="form-control" required>
            <option value="male" {{ $animal->gender == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ $animal->gender == 'female' ? 'selected' : '' }}>Female</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Birth Date</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', $animal->birth_date) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes', $animal->notes) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Add New Images (optional)</label>
        <input type="file" name="images[]" multiple class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update Animal</button>
    <a href="{{ route('animals.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection