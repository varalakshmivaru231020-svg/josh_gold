@extends('admin.layouts.app')

@section('title', 'Manage Publications / Products')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Publications / Products</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Publications / Products</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-book me-1"></i>
                    All Publications &amp; Products
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Publication
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cover</th>
                            <th>Name</th>
                            <th>Year / Label</th>
                            <th>Category</th>
                            <th>Icon</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->cover_image)
                                    <img src="{{ asset('storage/' . $product->cover_image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-thumbnail" 
                                         style="width: 50px; height: 65px; object-fit: cover; border: 1px solid #c9a84c;">
                                @elseif($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-thumbnail" 
                                         style="width: 50px; height: 65px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 65px; font-size: 1.5rem;">
                                        {{ $product->book_icon ?? '📚' }}
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>
                                @if($product->year)
                                    <span class="badge bg-secondary">{{ $product->year }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-info text-dark">{{ $product->category }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td style="font-size: 1.4rem; text-align: center;">{{ $product->book_icon ?? '' }}</td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $product->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $product->id }}"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>"{{ $product->name }}"</strong>?
                                                <br><small class="text-danger">This action cannot be undone.</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        pageLength: 10,
        responsive: true,
        order: [[0, 'desc']]
    });
});
</script>
@endpush
@endsection