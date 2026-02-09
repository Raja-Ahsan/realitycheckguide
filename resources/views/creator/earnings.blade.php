@extends('layouts.creator.app')

@section('title', 'Earnings')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Earnings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Creator Dashboard</a></li>
                        <li class="breadcrumb-item active">Earnings</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Total Earnings Card -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="text-white">${{ number_format($totalEarnings, 2) }}</h3>
                                    <p class="text-white mb-0">Total Earnings</p>
                                </div>
                                <div class="text-white">
                                    <i class="fas fa-dollar-sign fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="text-white">{{ $recentTransactions->total() }}</h3>
                                    <p class="text-white mb-0">Total Transactions</p>
                                </div>
                                <div class="text-white">
                                    <i class="fas fa-shopping-cart fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Recent Transactions
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            @if($recentTransactions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Video</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentTransactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->paid_at ? $transaction->paid_at->format('M d, Y') : $transaction->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $transaction->user->name }}</td>
                                                    <td>
                                                        <a href="{{ route('videos.show', $transaction->video) }}">
                                                            {{ Str::limit($transaction->video->title, 30) }}
                                                        </a>
                                                    </td>
                                                    <td>${{ number_format($transaction->amount, 2) }}</td>
                                                    <td>
                                                        @if($transaction->status === 'completed')
                                                            <span class="badge badge-success">Completed</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ ucfirst($transaction->status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <div class="card-footer">
                                    {{ $recentTransactions->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No transactions yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line"></i> Monthly Earnings
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($monthlyEarnings->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($monthlyEarnings as $earning)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $earning->month)->format('M Y') }}</strong>
                                            </div>
                                            <div>
                                                <span class="badge badge-success">${{ number_format($earning->earnings, 2) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No monthly earnings data yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
