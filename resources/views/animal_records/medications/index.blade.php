@extends('layouts.app')

@section('content')
    <h2>Medication Records</h2>
    <a href="{{ route('medications.create') }}" class="btn btn-success mb-3">Add Medication</a>

    @if ($medications->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Medication Name</th>
                    <th>Dosage</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medications as $medication)
                    <tr>
                        <td>{{ $medication->animal->type ?? 'N/A' }}</td>
                        <td>{{ $medication->medicine_name }}</td>
                        <td>{{ $medication->dosage }}</td>
                        <td>{{ $medication->administration_date }}</td>
                        <td>
                            <span class="badge bg-{{ $medication->is_completed ? 'success' : 'warning' }}">
                                {{ $medication->is_completed ? 'Completed' : 'In Progress' }}
                            </span>
                        </td>
                        <td>
                            @foreach ($medication->media as $media)
                                <img src="{{ asset('storage/' . $media->image_path) }}" width="50" class="me-1 mb-1">
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('medications.show', $medication) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('medications.edit', $medication) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('medications.destroy', $medication) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No medication records found.</p>
    @endif
@endsection
