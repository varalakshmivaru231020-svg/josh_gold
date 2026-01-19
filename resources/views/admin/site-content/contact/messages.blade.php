@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Contact Messages</h1>
    <span class="badge bg-primary">{{ $messages->total() }} Total</span>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">Status</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th width="150">Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr class="{{ $message->status === 'unread' ? 'table-warning' : '' }}">
                        <td>
                            @if($message->status === 'unread')
                            <span class="badge bg-warning text-dark"><i class="fas fa-envelope"></i></span>
                            @elseif($message->status === 'read')
                            <span class="badge bg-info"><i class="fas fa-envelope-open"></i></span>
                            @else
                            <span class="badge bg-success"><i class="fas fa-reply"></i></span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $message->name }}</strong>
                            @if($message->phone)
                            <br><small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $message->phone }}</small>
                            @endif
                        </td>
                        <td>{{ $message->email }}</td>
                        <td>{{ Str::limit($message->subject, 40) }}</td>
                        <td>{{ $message->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.site-content.contact-messages.view', $message->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.site-content.contact-messages.delete', $message->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No messages found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
        <div class="mt-3">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
