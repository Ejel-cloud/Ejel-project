@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">{{ $user->name }}’s Profile</h2>

    {{-- Profile Information --}}
    <div class="card mb-5">
        <div class="card-body">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            {{-- يمكنك إضافة حقول أخرى مثل phone أو address إذا كانت موجودة في جدول users --}}
        </div>
    </div>

    <h4 class="mb-3">Your Marketplace Listings</h4>
    @if($user->listings->isEmpty())
        <p class="text-muted">You haven’t posted any listings yet.</p>
    @else
        <div class="row">
            @foreach($user->listings as $listing)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($listing->animal && $listing->animal->media->first())
                            <img src="{{ asset($listing->animal->media->first()->file_path) }}"
                                 class="card-img-top"
                                 style="height:200px; object-fit:cover;"
                                 alt="Listing Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ \Illuminate\Support\Str::limit($listing->title, 30) }}
                            </h5>
                            <p class="text-success">
                                {{ number_format($listing->price, 2) }} SYP
                            </p>
                            <a href="{{ route('marketplace.show', $listing) }}"
                               class="btn btn-sm btn-primary">
                                View
                            </a>
                            <a href="{{ route('marketplace.edit', $listing) }}"
                               class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
