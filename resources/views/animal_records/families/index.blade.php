@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Family History Records</h2>
    <a href="{{ route('animal-records.families.create') }}" class="btn btn-success mb-3">Add Family Record</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Animal</th>
                <th>Relationship</th>
                <th>Description</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($families as $family)
            <tr>
                <td>{{ $family->animal->type ?? 'N/A' }}</td>
                <td>{{ $family->relation_type ?? '-' }}</td>
                <td>{{ $family->description ?? '-' }}</td>

                <td>
                    @foreach($family->media as $media)
                    <img src="{{ asset('storage/' . $media->path) }}" width="60">
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('animal-records.families.show', $family) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('animal-records.families.edit', $family) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('animal-records.families.destroy', $family) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection