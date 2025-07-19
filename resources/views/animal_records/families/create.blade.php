@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Family Record</h2>
    <form action="{{ route('animal-records.families.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Animal</label>
            <select name="animal_id" class="form-control" required>
                @foreach($animals as $animal)
                    <option value="{{ $animal->id }}">{{ $animal->type }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Relation</label>
            <input type="text" name="relation_type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>Images (up to 3)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
