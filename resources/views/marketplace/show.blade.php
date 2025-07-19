@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb اختياري --}}
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">الحراج</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $listing->title }}</li>
      </ol>
    </nav>

    {{-- 1. Header: العنوان والسعر --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
      <h2 class="fw-bold mb-2 mb-md-0">{{ $listing->title }}</h2>
      <div class="text-primary fs-4 fw-bold">{{ number_format($listing->price) }} SYP</div>
    </div>

    {{-- 2. Metadata --}}
    <div class="d-flex flex-wrap text-muted mb-4 gap-3">
      <div><i class="bi bi-geo-alt-fill"></i> {{ $listing->city }}</div>
      <div><i class="bi bi-clock-fill"></i> نُشر {{ $listing->created_at->diffForHumans() }}</div>
      <div><i class="bi bi-person-fill"></i> {{ $listing->user->name }}</div>
    </div>

    {{-- 3. الوصف --}}
    @if($listing->description)
    <div class="card mb-4">
      <div class="card-body p-3">
        <h5 class="card-title">Description</h5>
        <p class="card-text mb-0">{{ $listing->description }}</p>
      </div>
    </div>
    @endif

    {{-- 4. معرض الصور --}}
    @if($listing->animal && $listing->animal->media->count())
      <div id="listingCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded">
          @foreach($listing->animal->media as $idx => $media)
            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
              <a href="{{ asset($media->file_path) }}" target="_blank">
                <img src="{{ asset($media->file_path) }}"
                     class="d-block w-100"
                     style="max-height: 500px; object-fit: cover;"
                     alt="Image {{ $idx + 1 }}">
              </a>
            </div>
          @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    @endif

    {{-- 5. زر الشات الخاص --}}
    <div class="mb-3">
      <a href="{{ route('marketplace.chat.private', $listing->id) }}"
         class="btn btn-outline-secondary">
        🔒 الشات الخاص
      </a>
    </div>

    {{-- 6. الشات العام --}}
    <div class="card">
      <div class="card-header bg-white">
        <i class="bi bi-chat-dots-fill"></i> الشات العام
      </div>
      <div class="card-body" style="max-height: 300px; overflow-y: auto;">
        @forelse($chat->messages as $msg)
          <div class="mb-2">
            <strong>{{ $msg->user->name }}:</strong> {{ $msg->message }}
            <small class="text-muted d-block">{{ $msg->created_at->format('H:i') }}</small>
          </div>
        @empty
          <p class="text-muted">لا توجد رسائل بعد.</p>
        @endforelse
      </div>
      <div class="card-footer bg-white">
        <form action="{{ route('marketplace.chat.message', $chat->id) }}" method="POST">
          @csrf
          <div class="input-group">
            <input type="text"
                   name="message"
                   class="form-control"
                   placeholder="اكتب رسالتك هنا…"
                   required>
            <button class="btn btn-primary">إرسال</button>
          </div>
        </form>
      </div>
    </div>

</div>
@endsection
