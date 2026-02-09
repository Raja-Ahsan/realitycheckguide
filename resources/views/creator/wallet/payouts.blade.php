@extends('layouts.creator.app')

@section('title', 'Payouts')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Payouts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('creator.wallet.dashboard') }}">Wallet</a></li>
                        <li class="breadcrumb-item active">Payouts</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Payout Summary Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $payouts->where('status', 'pending')->count() }}</h3>
                            <p>Pending Payouts</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $payouts->where('status', 'completed')->count() }}</h3>
                            <p>Completed Payouts</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>${{ number_format($payouts->where('status', 'pending')->sum('amount'), 2) }}</h3>
                            <p>Pending Amount</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>${{ number_format($payouts->where('status', 'completed')->sum('amount'), 2) }}</h3>
                            <p>Total Paid Out</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request New Payout -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle"></i> Request New Payout
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('creator.wallet.request-payout') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">Amount ($)</label>
                                            <input type="number" 
                                                   class="form-control @error('amount') is-invalid @enderror" 
                                                   id="amount" 
                                                   name="amount" 
                                                   step="0.01" 
                                                   min="10.00" 
                                                   max="10000.00" 
                                                   value="{{ old('amount') }}" 
                                                   required>
                                            @error('amount')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">Minimum: $10.00, Maximum: $10,000.00</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payout_method">Payout Method</label>
                                            <select class="form-control @error('payout_method') is-invalid @enderror" 
                                                    id="payout_method" 
                                                    name="payout_method" 
                                                    required>
                                                <option value="">Select Method</option>
                                                <option value="stripe" {{ old('payout_method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                                <option value="bank_transfer" {{ old('payout_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                <option value="manual" {{ old('payout_method') == 'manual' ? 'selected' : '' }}>Manual</option>
                                            </select>
                                            @error('payout_method')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Request Payout
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payouts Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Payout History
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            @if($payouts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Payout #</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Status</th>
                                                <th>Requested</th>
                                                <th>Processed</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payouts as $payout)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $payout->payout_number }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="text-success font-weight-bold">
                                                            ${{ number_format($payout->amount, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ ucfirst(str_replace('_', ' ', $payout->payout_method)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($payout->status === 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif($payout->status === 'processing')
                                                            <span class="badge badge-info">Processing</span>
                                                        @elseif($payout->status === 'completed')
                                                            <span class="badge badge-success">Completed</span>
                                                        @elseif($payout->status === 'failed')
                                                            <span class="badge badge-danger">Failed</span>
                                                        @elseif($payout->status === 'cancelled')
                                                            <span class="badge badge-secondary">Cancelled</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $payout->created_at->format('M d, Y H:i') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($payout->processed_at)
                                                            <small class="text-muted">
                                                                {{ $payout->processed_at->format('M d, Y H:i') }}
                                                            </small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($payout->status === 'pending')
                                                            <form action="{{ route('creator.wallet.cancel-payout', $payout) }}" 
                                                                  method="POST" 
                                                                  style="display: inline;"
                                                                  onsubmit="return confirm('Are you sure you want to cancel this payout request?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-times"></i> Cancel
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="card-footer clearfix">
                                    {{ $payouts->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No payout requests yet.</p>
                                    <p class="text-muted">Request your first payout when you have sufficient balance.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout Information -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Payout Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Processing Times</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Stripe:</strong> 1-3 business days</li>
                                        <li><strong>Bank Transfer:</strong> 3-5 business days</li>
                                        <li><strong>Manual:</strong> 5-10 business days</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Requirements</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Minimum Amount:</strong> $10.00</li>
                                        <li><strong>Maximum Amount:</strong> $10,000.00</li>
                                        <li><strong>Available Balance:</strong> Must have sufficient funds</li>
                                        <li><strong>Account Verification:</strong> Required for first payout</li>
                                    </ul>
                                </div>
                            </div>
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
    // Auto-refresh every 30 seconds for pending payouts
    setInterval(function() {
        if ({{ $payouts->where('status', 'pending')->count() }} > 0) {
            location.reload();
        }
    }, 30000);
});
</script>
@endpush
