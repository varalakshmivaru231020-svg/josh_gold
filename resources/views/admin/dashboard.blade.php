@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Overview</li>
    </ol>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Total Orders</h6>
                            <h4 class="mb-0">{{ $stats['total_orders'] }}</h4>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.orders.index') }}">
                        View Details
                    </a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Total Revenue</h6>
                            <h4 class="mb-0">₹{{ number_format($stats['total_revenue'], 2) }}</h4>
                        </div>
                        <i class="fas fa-rupee-sign fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.orders.index') }}">
                        View Details
                    </a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Total Agents</h6>
                            <h4 class="mb-0">{{ $stats['total_agents'] }}</h4>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.agents.index') }}">
                        View Details
                    </a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Pending Settlements</h6>
                            <h4 class="mb-0">₹{{ number_format($stats['pending_settlements'], 2) }}</h4>
                        </div>
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.settlements.index') }}">
                        View Details
                    </a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Revenue Analysis (Last 30 Days)
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Quick Stats
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Today's Orders</span>
                        <strong id="todayOrders">0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>This Week's Revenue</span>
                        <strong id="weekRevenue">₹0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Monthly Commissions</span>
                        <strong id="monthCommissions">₹0</strong>
                    </div>
                    <div class="mt-4">
                        <select class="form-select" id="periodSelect">
                            <option value="daily">Today</option>
                            <option value="weekly">This Week</option>
                            <option value="monthly" selected>This Month</option>
                            <option value="yearly">This Year</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Top Agents -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Recent Orders
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>₹{{ $order->total }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-trophy me-1"></i>
                    Top Performing Agents
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Agent</th>
                                    <th>Referrals</th>
                                    <th>Earnings</th>
                                    <th>Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topAgents as $index => $agent)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-light rounded-circle">
                                                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $agent->name }}</h6>
                                                <small class="text-muted">{{ $agent->referral_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $agent->referrals_count }}</td>
                                    <td>₹{{ number_format($agent->total_commission ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                            #{{ $index + 1 }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($dailyEarnings->pluck('date')),
        datasets: [{
            label: 'Daily Revenue',
            data: @json($dailyEarnings->pluck('earnings')),
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            pointBackgroundColor: '#4e73df',
            pointBorderColor: '#4e73df',
            pointRadius: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value;
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: ₹' + context.parsed.y.toFixed(2);
                    }
                }
            }
        }
    }
});

// Load quick stats
function loadStats(period) {
    fetch(`{{ route('admin.dashboard.stats') }}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('todayOrders').textContent = data.total_orders || 0;
            document.getElementById('weekRevenue').textContent = '₹' + (data.total_revenue || 0).toFixed(2);
            document.getElementById('monthCommissions').textContent = '₹' + (data.total_commissions || 0).toFixed(2);
        });
}

document.getElementById('periodSelect').addEventListener('change', function() {
    loadStats(this.value);
});

// Load initial stats
loadStats('monthly');
</script>
@endpush