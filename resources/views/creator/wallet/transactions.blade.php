@extends('layouts.creator.app')

@section('title', 'Wallet Transactions')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Wallet Transactions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('creator.wallet.dashboard') }}">Wallet</a></li>
                        <li class="breadcrumb-item active">Transactions</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Transaction Summary Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>${{ number_format($wallet->total_earned, 2) }}</h3>
                            <p>Total Earned</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
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
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>${{ number_format($wallet->total_paid_out, 2) }}</h3>
                            <p>Total Paid Out</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Transaction History
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('creator.wallet.dashboard') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-wallet"></i> Back to Wallet
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($transactions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Balance Before</th>
                                                <th>Balance After</th>
                                                <th>Reference</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transactions as $transaction)
                                                <tr>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $transaction->created_at->format('M d, Y H:i') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($transaction->type === 'video_sale')
                                                            <span class="badge badge-success">Video Sale</span>
                                                        @elseif($transaction->type === 'payout_request')
                                                            <span class="badge badge-warning">Payout Request</span>
                                                        @elseif($transaction->type === 'payout_completed')
                                                            <span class="badge badge-info">Payout Completed</span>
                                                        @elseif($transaction->type === 'payout_cancelled')
                                                            <span class="badge badge-secondary">Payout Cancelled</span>
                                                        @elseif($transaction->type === 'commission_deduction')
                                                            <span class="badge badge-danger">Commission</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $transaction->description }}</strong>
                                                        @if($transaction->reference_id)
                                                            <br><small class="text-muted">ID: {{ $transaction->reference_id }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($transaction->amount > 0)
                                                            <span class="text-success font-weight-bold">
                                                                +${{ number_format($transaction->amount, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="text-danger font-weight-bold">
                                                                ${{ number_format($transaction->amount, 2) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            ${{ number_format($transaction->balance_before, 2) }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            ${{ number_format($transaction->balance_after, 2) }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($transaction->reference_id)
                                                            <small class="text-muted">
                                                                #{{ $transaction->reference_id }}
                                                            </small>
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
                                    {{ $transactions->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No transactions yet.</p>
                                    <p class="text-muted">Transactions will appear here when you start earning from video sales.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Types Legend -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Transaction Types
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Income Transactions</h5>
                                    <ul class="list-unstyled">
                                        <li><span class="badge badge-success">Video Sale</span> - Earnings from video purchases</li>
                                        <li><span class="badge badge-info">Payout Cancelled</span> - Refund when payout is cancelled</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Deduction Transactions</h5>
                                    <ul class="list-unstyled">
                                        <li><span class="badge badge-warning">Payout Request</span> - Amount reserved for payout</li>
                                        <li><span class="badge badge-danger">Commission</span> - Admin commission deduction</li>
                                        <li><span class="badge badge-info">Payout Completed</span> - Amount paid out to creator</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh every 2 minutes for real-time updates
    setInterval(function() {
        location.reload();
    }, 120000);
});
</script>
@endpush
