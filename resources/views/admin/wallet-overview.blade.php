@extends('layouts.admin.app')

@section('title', $page_title)

@section('content')
    <section class="content-header">
        <h1>{{ $page_title }}</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content">
        <!-- Wallet Summary -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-credit-card"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Balance</span>
                        <span class="info-box-number">${{ number_format($totalBalance, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Balance</span>
                        <span class="info-box-number">${{ number_format($totalPendingBalance, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-plus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Earned</span>
                        <span class="info-box-number">${{ number_format($totalEarned, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-minus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Paid Out</span>
                        <span class="info-box-number">${{ number_format($totalPaidOut, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Recent Wallet Transactions</h3>
            </div>
            <div class="box-body">
                @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Creator</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Balance Before</th>
                                    <th>Balance After</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <strong>{{ $transaction->wallet->creator->name ?? 'Unknown' }}</strong><br>
                                        <small>{{ $transaction->wallet->creator->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if($transaction->type === 'credit')
                                            <span class="label label-success">Credit</span>
                                        @elseif($transaction->type === 'debit')
                                            <span class="label label-danger">Debit</span>
                                        @elseif($transaction->type === 'payout_request')
                                            <span class="label label-warning">Payout Request</span>
                                        @else
                                            <span class="label label-info">{{ ucfirst($transaction->type) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type === 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                        </strong>
                                    </td>
                                    <td>${{ number_format($transaction->balance_before, 2) }}</td>
                                    <td>${{ number_format($transaction->balance_after, 2) }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-muted">No transactions found.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
