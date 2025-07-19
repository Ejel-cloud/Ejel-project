@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Private Chat with {{ $listing->user->name }}</h2>

    <div id="messages" class="mb-3" style="max-height: 400px; overflow-y: auto;">
        @foreach($chat->messages as $msg)
            <div class="mb-2">
                <strong>{{ $msg->user->name }}:</strong> {{ $msg->message }}
                <small class="text-muted">{{ $msg->created_at->format('H:i') }}</small>
            </div>
        @endforeach
    </div>

    <form id="message-form" action="{{ route('marketplace.chat.message', $chat->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message…" required>
            <button class="btn btn-primary">Send</button>
        </div>
    </form>
</div>
@endsection
