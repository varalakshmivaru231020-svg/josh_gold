@extends('admin.layouts.app')

@section('title', 'Manage WhatsApp Flow Steps')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">WhatsApp Flow Steps</h1>
    <a href="{{ route('admin.site-content.whatsapp-flow.create') }}" class="btn btn-success">
        <i class="fab fa-whatsapp me-2"></i>Add Step
    </a>
</div>

<div class="alert alert-success">
    <i class="fab fa-whatsapp me-2"></i>
    These steps show how customers can order via WhatsApp. They appear on the homepage in a green-themed section.
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
                        <td><span class="badge bg-success">{{ $step->step_number }}</span></td>
                        <td><i class="{{ $step->icon }} fa-2x text-success"></i></td>
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
                            <a href="{{ route('admin.site-content.whatsapp-flow.edit', $step->id) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.whatsapp-flow.delete', $step->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this step?');">
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
                        <td colspan="6" class="text-center text-muted py-4">No WhatsApp flow steps found. Add your first step!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fab fa-whatsapp me-2"></i>Suggested Steps</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Here are some common WhatsApp ordering steps you might want to add:</p>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border border-success rounded p-3 text-center">
                    <i class="fab fa-whatsapp fa-2x text-success mb-2"></i>
                    <h6>Open WhatsApp</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border border-success rounded p-3 text-center">
                    <i class="fas fa-comment fa-2x text-success mb-2"></i>
                    <h6>Send Book Name</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border border-success rounded p-3 text-center">
                    <i class="fas fa-map-marker-alt fa-2x text-success mb-2"></i>
                    <h6>Share Address</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border border-success rounded p-3 text-center">
                    <i class="fas fa-check-double fa-2x text-success mb-2"></i>
                    <h6>Confirm Order</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
