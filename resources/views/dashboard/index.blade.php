@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">📊 My Dashboard</h1>

    {{-- أول صف من الكروت --}}
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow rounded-4 text-center p-4">
                <h5>Total Animals</h5>
                <h2>{{ $totalAnimals }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow rounded-4 text-center p-4">
                <h5>Animals for Sale</h5>
                <h2>{{ $animalsForSale }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow rounded-4 text-center p-4">
                <h5>Marketplace Listings</h5>
                <h2>{{ $totalListings }}</h2>
            </div>
        </div>
    </div>

    {{-- الكروت الجديدة الخاصة بالسجلات --}}
    <div class="row g-4 mt-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center p-4 shadow rounded-4">
                <h5>Diseases</h5>
                <h2>{{ $totalDiseases }}</h2>
                <a href="{{ route('animal-records.diseases.index') }}" class="btn btn-light btn-sm mt-2">View</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center p-4 shadow rounded-4">
                <h5>Vaccines</h5>
                <h2>{{ $totalVaccines }}</h2>
                <a href="{{ route('animal-records.vaccines.index') }}" class="btn btn-light btn-sm mt-2">View</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center p-4 shadow rounded-4">
                <h5>Family Records</h5>
                <h2>{{ $totalFamilies }}</h2>
                <a href="{{ route('animal-records.families.index') }}" class="btn btn-light btn-sm mt-2">View</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white text-center p-4 shadow rounded-4">
                <h5>Medications</h5>
                <h2>{{ $totalMedications }}</h2>
                <a href="{{ route('animal-records.medications.index') }}" class="btn btn-light btn-sm mt-2">View</a>
            </div>
        </div>
    </div>

    {{-- جدول الحيوانات المضافة حديثاً --}}
    <div class="mt-5">
        <h4>Recently Added Animals</h4>
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Gender</th>
                    <th>Health</th>
                    <th>Birth Date</th>
                    <th>For Sale</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentAnimals as $animal)
                <tr>
                    <td>{{ $animal->type }}</td>
                    <td>{{ ucfirst($animal->gender) }}</td>
                    <td>{{ ucfirst($animal->health_status) }}</td>
                    <td>{{ $animal->birth_date ?? '-' }}</td>
                    <td>{{ $animal->for_sale ? 'Yes' : 'No' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No animals found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
