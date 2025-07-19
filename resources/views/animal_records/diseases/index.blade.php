@extends('layouts.app')

@section('content')
    <h2>Disease Records</h2>
    <a href="{{ route('animal-records.diseases.create') }}" class="btn btn-success mb-3">Add Disease Record</a>

    @if ($diseases->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Name</th>
                    <th>Diagnosis Date</th>
                    <th>Status</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($diseases as $disease)
                    <tr>
                        <td>{{ $disease->animal->type ?? 'N/A' }}</td>
                        <td>{{ $disease->name }}</td>
                        <td>{{ $disease->diagnosed_at }}</td>
                        <td>{{ $disease->is_cured ? 'Cured' : 'In Treatment' }}</td>
                        <td>
                            @foreach ($disease->media as $media)
                                <img src="{{ asset('storage/' . $media->file_path) }}" width="50" class="img-thumbnail me-1">
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('animal-records.diseases.show', $disease) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('animal-records.diseases.edit', $disease) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('animal-records.diseases.destroy', $disease) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $diseases->links() }}
    @else
        <p>No disease records found.</p>
    @endif
@endsection
