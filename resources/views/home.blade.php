@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Welcome to Ejel Livestock App</h1>

    <div class="d-flex gap-3">
        <a href="{{ route('animals.index') }}" class="btn btn-primary">My Animals</a>
        <a href="{{ route('animals.create') }}" class="btn btn-success">Add Animal</a>
        <a href="{{ route('marketplace.index') }}" class="btn btn-warning">Marketplace</a>
    </div>
</div>
@endsection
