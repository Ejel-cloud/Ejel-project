@extends('layouts.app')

@section('content')
    <h2>Vaccine Records</h2>

    <a href="{{ route('animal-records.vaccines.create') }}" class="btn btn-success mb-3">Add Vaccine Record</a>

    @if ($vaccines->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Vaccine Name</th>
                    <th>Vaccine Type</th>
                    <th>Vaccination Date</th>
                    <th>Next Vaccination Date</th>
                    <th>Veterinarian</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vaccines as $vaccine)
                    <tr>
                        <td>{{ $vaccine->animal->type ?? 'N/A' }}</td>
                        <td>{{ $vaccine->name }}</td>
                        <td>{{ $vaccine->vaccine_type }}</td>
                        <td>{{ $vaccine->vaccination_date }}</td>
                        <td>{{ $vaccine->next_vaccination_date ?? '-' }}</td>
                        <td>{{ $vaccine->veterinarian ?? '-' }}</td>
                        <td>
                            @foreach ($vaccine->media as $media)
                                <img src="{{ asset('storage/' . $media->file_path) }}" width="50" class="me-1 mb-1 rounded">
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('animal-records.vaccines.show', $vaccine) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('animal-records.vaccines.edit', $vaccine) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('animal-records.vaccines.destroy', $vaccine) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $vaccines->links() }} {{-- Laravel pagination --}}
    @else
        <p>No vaccine records found.</p>
    @endif
@endsection
