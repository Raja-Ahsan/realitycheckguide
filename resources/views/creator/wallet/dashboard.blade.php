@extends('layouts.creator.app')

@section('title', 'Wallet Dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Wallet Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Creator Dashboard</a></li>
                        <li class="breadcrumb-item active">Wallet</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Wallet Balance Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>${{ number_format($wallet->balance, 2) }}</h3>
                            <p>Available Balance</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>${{ number_format($wallet->pending_balance, 2) }}</h3>
                            <p>Pending Balance</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>${{ number_format($wallet->total_earned, 2) }}</h3>
                            <p>Total Earned</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>${{ number_format($monthlyEarnings, 2) }}</h3>
                            <p>This Month</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Request Payout Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-money-bill-wave"></i> Request Payout
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($wallet->balance >= 10.00)
                                <form action="{{ route('creator.wallet.request-payout') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" class="form-control" id="amount" name="amount" 
                                                   min="10.00" max="{{ $wallet->balance }}" step="0.01" 
                                                   value="{{ min(100.00, $wallet->balance) }}" required>
                                        </div>
                                        <small class="form-text text-muted">
                                            Min: $10.00 | Max: ${{ number_format($wallet->balance, 2) }}
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="payout_method">Payout Method</label>
                                        <select class="form-control" id="payout_method" name="payout_method" required>
                                            <option value="">Select Method</option>
                                            <option value="stripe">Stripe</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="manual">Manual</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-paper-plane"></i> Request Payout
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <p class="text-muted">You need at least $10.00 to request a payout.</p>
                                    <p class="text-muted">Current balance: ${{ number_format($wallet->balance, 2) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Recent Transactions
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('creator.wallet.transactions') }}" class="btn btn-primary btn-sm">
                                    View All
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($recentTransactions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentTransactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                                    <td>
                                                        @switch($transaction->type)
                                                            @case('credit')
                                                                <span class="badge badge-success">Credit</span>
                                                                @break
                                                            @case('debit')
                                                                <span class="badge badge-danger">Debit</span>
                                                                @break
                                                            @case('payout_request')
                                                                <span class="badge badge-warning">Payout Request</span>
                                                                @break
                                                            @case('payout_cancelled')
                                                                <span class="badge badge-secondary">Payout Cancelled</span>
                                                                @break
                                                            @default
                                                                <span class="badge badge-info">{{ ucfirst($transaction->type) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $transaction->description }}</td>
                                                    <td>
                                                        @if($transaction->type === 'credit')
                                                            <span class="text-success">+${{ number_format($transaction->amount, 2) }}</span>
                                                        @else
                                                            <span class="text-danger">-${{ number_format($transaction->amount, 2) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($transaction->balance_after, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No transactions yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payouts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-money-bill-wave"></i> Recent Payout Requests
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('creator.wallet.payouts') }}" class="btn btn-primary btn-sm">
                                    View All
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($recentPayouts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Payout #</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPayouts as $payout)
                                                <tr>
                                                    <td>{{ $payout->payout_number }}</td>
                                                    <td>${{ number_format($payout->amount, 2) }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $payout->payout_method)) }}</td>
                                                    <td>
                                                        @switch($payout->status)
                                                            @case('pending')
                                                                <span class="badge badge-warning">Pending</span>
                                                                @break
                                                            @case('approved')
                                                                <span class="badge badge-info">Approved</span>
                                                                @break
                                                            @case('processing')
                                                                <span class="badge badge-primary">Processing</span>
                                                                @break
                                                            @case('completed')
                                                                <span class="badge badge-success">Completed</span>
                                                                @break
                                                            @case('rejected')
                                                                <span class="badge badge-danger">Rejected</span>
                                                                @break
                                                            @case('cancelled')
                                                                <span class="badge badge-secondary">Cancelled</span>
                                                                @break
                                                            @default
                                                                <span class="badge badge-info">{{ ucfirst($payout->status) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $payout->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        @if($payout->isPending())
                                                            <form action="{{ route('creator.wallet.cancel-payout', $payout) }}" 
                                                                  method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                                        onclick="return confirm('Are you sure you want to cancel this payout request?')">
                                                                    <i class="fas fa-times"></i> Cancel
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No payout requests yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh wallet data every 2 minutes
    setInterval(function() {
        location.reload();
    }, 120000);
});
</script>
@endpush
