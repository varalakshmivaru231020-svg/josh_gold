@extends('admin.layouts.app')

@section('title', 'Manage Order Flow Steps')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Order Flow Steps</h1>
    <a href="{{ route('admin.site-content.order-flow.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Step
    </a>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    These steps show how customers can order on your website. They appear on the homepage as a horizontal timeline.
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Step #</th>
                        <th width="80">Icon</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($steps as $step)
                    <tr>
                        <td><span class="badge bg-primary">{{ $step->step_number }}</span></td>
                        <td><i class="{{ $step->icon }} fa-2x text-primary"></i></td>
                        <td><strong>{{ $step->title }}</strong></td>
                        <td>{{ Str::limit($step->description, 50) }}</td>
                        <td>
                            @if($step->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.order-flow.edit', $step->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.order-flow.delete', $step->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this step?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No order flow steps found. Add your first step!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0">Suggested Steps</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Here are some common order flow steps you might want to add:</p>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <i class="fas fa-search fa-2x text-primary mb-2"></i>
                    <h6>Browse Products</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                    <h6>Select Book</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                    <h6>Make Payment</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                    <h6>Get Delivery</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
