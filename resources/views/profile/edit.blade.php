@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2>Edit Profile</h2>

  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Avatar</label>
      <input type="file" name="avatar" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name"
             value="{{ old('name',$user->name) }}"
             class="form-control @error('name') is-invalid @enderror" required>
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">City</label>
      <input type="text" name="city"
             value="{{ old('city',$user->city) }}"
             class="form-control @error('city') is-invalid @enderror">
      @error('city')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">About Me</label>
      <textarea name="bio" rows="4"
                class="form-control @error('bio') is-invalid @enderror">{{ old('bio',$user->bio) }}</textarea>
      @error('bio')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <button class="btn btn-primary">Save Changes</button>
    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
  </form>
</div>
@endsection
