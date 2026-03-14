@extends('admin.layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Orders</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Orders</li>
    </ol>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Orders
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Order #, Name, Mobile...">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.orders.export') }}?{{ http_build_query(request()->query()) }}" 
                               class="btn btn-success ms-auto">
                                <i class="fas fa-download me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Order List
                </div>
                <span class="badge bg-primary">Total: {{ $orders->total() }} orders</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold">
                                    {{ $order->order_number }}
                                </a>
                                @if($order->referral_code)
                                    <br><small class="text-success">Ref: {{ $order->referral_code }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong><br>
                                <small>{{ $order->customer_mobile }}</small>
                            </td>
                            <td>
                                @if($order->product)
                                    {{ Str::limit($order->product->name, 30) }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $order->quantity }}</td>
                            <td>
                                ₹{{ number_format($order->total, 2) }}
                                @if($order->discount > 0)
                                    <br><small class="text-success">-₹{{ number_format($order->discount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : 
                                                         ($order->order_status === 'cancelled' ? 'danger' : 
                                                         ($order->order_status === 'pending' ? 'warning' : 'info')) }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                @if($order->tracking_id)
                                    <br><small class="text-muted">Track: {{ $order->tracking_id }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#statusModal{{ $order->id }}"
                                            title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              <br>
@if ($orders->hasPages())
    <nav class="mt-3">
        <ul class="pagination justify-content-center">

            {{-- Previous Page --}}
            @if ($orders->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">Previous</a></li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                @if ($page == $orders->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next Page --}}
            @if ($orders->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif

        </ul>
    </nav>
@endif


            </div>
        </div>
    </div>
</div>

<!-- Status Update Modals -->
@foreach($orders as $order)
<div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="order_status{{ $order->id }}" class="form-label">Order Status</label>
                        <select class="form-select" id="order_status{{ $order->id }}" name="order_status" required>
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tracking_id{{ $order->id }}" class="form-label">Tracking ID</label>
                        <input type="text" class="form-control" id="tracking_id{{ $order->id }}" 
                               name="tracking_id" value="{{ $order->tracking_id }}" 
                               placeholder="Enter tracking ID">
                    </div>
                    
                    <div class="mb-3">
                        <label for="tracking_url{{ $order->id }}" class="form-label">Tracking URL</label>
                        <input type="url" class="form-control" id="tracking_url{{ $order->id }}" 
                               name="tracking_url" value="{{ $order->tracking_url }}" 
                               placeholder="https://example.com/track/...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes{{ $order->id }}" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes{{ $order->id }}" name="notes" 
                                  rows="3" placeholder="Any additional notes...">{{ $order->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')

@endpush
@endsection