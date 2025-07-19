@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Create Marketplace Listing</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following problems:
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('marketplace.store') }}" method="POST">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
            <input type="text"
                   name="title"
                   id="title"
                   value="{{ old('title') }}"
                   class="form-control @error('title') is-invalid @enderror"
                   maxlength="150"
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Selected Animal or Dropdown --}}
        @if(isset($selectedAnimal))
            <div class="mb-3">
                <label class="form-label">Selected Animal:</label>
                <div class="border p-2 mb-2">
                    <strong>Type:</strong> {{ $selectedAnimal->type }}<br>
                    <strong>Gender:</strong> {{ $selectedAnimal->gender }}<br>
                    <strong>Age:</strong> {{ $selectedAnimal->age }}
                </div>
                <input type="hidden" name="animal_id" value="{{ $selectedAnimal->id }}">
            </div>
        @else
            <div class="mb-3">
                <label for="animal_id" class="form-label">Choose Animal<span class="text-danger">*</span></label>
                <select name="animal_id" id="animal_id" class="form-select @error('animal_id') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @foreach($userAnimals as $animal)
                        <option value="{{ $animal->id }}" {{ old('animal_id') == $animal->id ? 'selected' : '' }}>
                            #{{ $animal->id }} — {{ ucfirst($animal->type) }} ({{ $animal->age }})
                        </option>
                    @endforeach
                </select>
                @error('animal_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endif

        {{-- Category --}}
        <div class="mb-3">
            <label for="category" class="form-label">Category<span class="text-danger">*</span></label>
            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                <option value="animal" {{ old('category')=='animal' ? 'selected':'' }}>Animal</option>
                <option value="feed"   {{ old('category')=='feed'   ? 'selected':'' }}>Feed</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Animal Type --}}
        <div class="mb-3">
            <label for="animal_type" class="form-label">Animal Type (optional)</label>
            <select name="animal_type" id="animal_type" class="form-select">
                <option value="">— Use original —</option>
                @foreach($animalTypes as $type)
                    <option value="{{ $type }}" {{ old('animal_type')==$type ? 'selected':'' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- City --}}
        <div class="mb-3">
            <label for="city" class="form-label">City<span class="text-danger">*</span></label>
            <select name="city" id="city" class="form-select @error('city') is-invalid @enderror" required>
                <option value="">-- Select City --</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ old('city')==$city ? 'selected':'' }}>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <label for="price" class="form-label">Price (SYP)<span class="text-danger">*</span></label>
            <input type="number"
                   name="price"
                   id="price"
                   class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price') }}"
                   min="0"
                   step="0.01"
                   required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description"
                      id="description"
                      rows="4"
                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-success">Publish Listing</button>
        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
