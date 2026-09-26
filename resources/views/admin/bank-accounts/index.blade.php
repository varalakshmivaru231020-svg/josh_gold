@extends('admin.layouts.app')

@section('title', 'Manage Bank Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Bank Details</h1>
    <a href="{{ route('admin.bank-accounts.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Bank Account
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Account Holder</th>
                        <th>Account No.</th>
                        <th>IFSC</th>
                        <th>UPI</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $account->bank_name }}</div>
                            @if($account->branch)<small class="text-muted">{{ $account->branch }}</small>@endif
                        </td>
                        <td>{{ $account->account_holder }}</td>
                        <td>{{ $account->account_number }}</td>
                        <td>{{ $account->ifsc_code }}</td>
                        <td>{{ $account->upi_id ?: '—' }}</td>
                        <td>{{ $account->sort_order }}</td>
                        <td>
                            @if($account->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Hidden</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.bank-accounts.destroy', $account) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bank account?');">
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
                        <td colspan="8" class="text-center text-muted py-4">No bank accounts yet. Add your first one!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accounts->hasPages())
        <div class="mt-3">
            {{ $accounts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
