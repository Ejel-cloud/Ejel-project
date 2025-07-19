@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Family Record</h2>
    <form action="{{ route('families.update', $family) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Relation</label>
            <input type="text" name="relation_type" value="{{ $family->relation_type }}">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $family->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Add New Images (up to 3)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
