@extends('admin.layouts.app')

@section('title', 'Create Settlement')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Create Settlement</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.settlements.index') }}">Settlements</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-hand-holding-usd me-1"></i>
                    New Settlement
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settlements.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="agent_id" class="form-label">Select Agent *</label>
                            <select class="form-select @error('agent_id') is-invalid @enderror" 
                                    id="agent_id" name="agent_id" required>
                                <option value="">-- Select Agent --</option>
                                @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" 
                                        {{ old('agent_id', request('agent_id')) == $agent->id ? 'selected' : '' }}
                                        data-pending="{{ $agent->pending_amount }}">
                                    {{ $agent->name }} ({{ $agent->referral_code }}) - 
                                    Pending: ₹{{ number_format($agent->pending_amount, 2) }}
                                </option>
                                @endforeach
                            </select>
                            @error('agent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₹) *</label>
                            <input type="number" step="0.01" min="1" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="pendingAmount"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method *</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">-- Select Method --</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID</label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" 
                                   id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Transaction Date</label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" 
                                   id="transaction_date" name="transaction_date" value="{{ old('transaction_date') }}">
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes *</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4" required>{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.settlements.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Settlement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Agent Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Agent Information
                </div>
                <div class="card-body">
                    <div id="agentInfo">
                        <p class="text-muted">Select an agent to view details</p>
                    </div>
                </div>
            </div>
            
            <!-- Pending Commissions -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Pending Commissions
                </div>
                <div class="card-body">
                    <div id="pendingCommissions">
                        <p class="text-muted">Select an agent to view pending commissions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const agents = @json($agents->keyBy('id'));
    
    $('#agent_id').change(function() {
        const agentId = $(this).val();
        const agent = agents[agentId];
        
        if (agent) {
            // Update agent info
            $('#agentInfo').html(`
                <h6>${agent.name}</h6>
                <p class="mb-1"><strong>Referral Code:</strong> ${agent.referral_code}</p>
                <p class="mb-1"><strong>Mobile:</strong> ${agent.mobile}</p>
                <p class="mb-1"><strong>Email:</strong> ${agent.email || 'N/A'}</p>
                <hr>
                <p class="mb-1"><strong>Total Earnings:</strong> ₹${parseFloat(agent.total_earnings).toFixed(2)}</p>
                <p class="mb-1"><strong>Settled Amount:</strong> ₹${parseFloat(agent.settled_amount).toFixed(2)}</p>
                <p class="mb-1"><strong>Pending Amount:</strong> ₹${parseFloat(agent.pending_amount).toFixed(2)}</p>
            `);
            
            // Update pending amount hint
            const pendingAmount = parseFloat(agent.pending_amount);
            $('#pendingAmount').html(`Maximum settlement amount: ₹${pendingAmount.toFixed(2)}`);
            
            // Set max amount
            $('#amount').attr('max', pendingAmount);
            
            // Load pending commissions via AJAX
            loadPendingCommissions(agentId);
        } else {
            $('#agentInfo').html('<p class="text-muted">Select an agent to view details</p>');
            $('#pendingAmount').html('');
            $('#amount').removeAttr('max');
            $('#pendingCommissions').html('<p class="text-muted">Select an agent to view pending commissions</p>');
        }
    });
    
    function loadPendingCommissions(agentId) {
        $.ajax({
            url: `/admin/agents/${agentId}/pending-commissions`,
            method: 'GET',
            success: function(data) {
                if (data.length > 0) {
                    let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Order #</th><th>Amount</th></tr></thead><tbody>';
                    let total = 0;
                    
                    data.forEach(commission => {
                        html += `<tr><td>${commission.order_number}</td><td>₹${parseFloat(commission.amount).toFixed(2)}</td></tr>`;
                        total += parseFloat(commission.amount);
                    });
                    
                    html += `</tbody></table></div><p class="mt-2"><strong>Total Pending:</strong> ₹${total.toFixed(2)}</p>`;
                    $('#pendingCommissions').html(html);
                } else {
                    $('#pendingCommissions').html('<p class="text-muted">No pending commissions</p>');
                }
            },
            error: function() {
                $('#pendingCommissions').html('<p class="text-danger">Failed to load commissions</p>');
            }
        });
    }
    
    // Validate amount against pending amount
    $('#amount').on('input', function() {
        const agentId = $('#agent_id').val();
        if (agentId) {
            const agent = agents[agentId];
            const amount = parseFloat($(this).val()) || 0;
            const pendingAmount = parseFloat(agent.pending_amount);
            
            if (amount > pendingAmount) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
                $(this).after('<div class="invalid-feedback">Amount cannot exceed pending amount: ₹' + pendingAmount.toFixed(2) + '</div>');
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        }
    });
    
    // Trigger change event if agent is pre-selected
    if ($('#agent_id').val()) {
        $('#agent_id').trigger('change');
    }
});
</script>
@endpush
@endsection