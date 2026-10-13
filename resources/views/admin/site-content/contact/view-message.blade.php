@extends('admin.layouts.app')

@section('title', 'View Message')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">View Message</h1>
    <a href="{{ route('admin.site-content.contact-messages') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Messages
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $message->subject }}</h5>
                @if($message->status === 'unread')
                <span class="badge bg-warning text-dark">Unread</span>
                @elseif($message->status === 'read')
                <span class="badge bg-info">Read</span>
                @else
                <span class="badge bg-success">Replied</span>
                @endif
            </div>
            <div class="card-body">
                <div class="message-content p-3 bg-light rounded" style="white-space: pre-line;">
                    {{ $message->message }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Contact Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Name</label>
                    <p class="mb-0"><strong>{{ $message->name }}</strong></p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <p class="mb-0">
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </p>
                </div>

                @if($message->phone)
                <div class="mb-3">
                    <label class="text-muted small">Phone</label>
                    <p class="mb-0">
                        <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                    </p>
                </div>
                @endif

                <div class="mb-3">
                    <label class="text-muted small">Received</label>
                    <p class="mb-0">{{ $message->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-primary">
                        <i class="fas fa-reply me-2"></i>Reply via Email
                    </a>
                    @if($message->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp me-2"></i>Reply via WhatsApp
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('admin.site-content.contact-messages.delete', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-2"></i>Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
