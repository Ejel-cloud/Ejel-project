@extends('layouts.app')

@section('content')
    <h2>Disease Details</h2>

    <ul class="list-group">
        <li class="list-group-item"><strong>Animal:</strong> {{ $disease->animal->type ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Name:</strong> {{ $disease->name }}</li>
        <li class="list-group-item"><strong>Symptoms:</strong> {{ $disease->symptoms }}</li>
        <li class="list-group-item"><strong>Treatment:</strong> {{ $disease->treatment }}</li>
        <li class="list-group-item"><strong>Diagnosis Date:</strong> {{ $disease->diagnosed_at }}</li>
        <li class="list-group-item"><strong>Recovery Date:</strong> {{ $disease->recovery_date ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Veterinarian:</strong> {{ $disease->veterinarian ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ $disease->is_cured ? 'Cured' : 'In Treatment' }}</li>
        <li class="list-group-item"><strong>Notes:</strong> {{ $disease->notes }}</li>
        <li class="list-group-item">
            <strong>Images:</strong><br>
            @foreach ($d
