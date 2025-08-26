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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Creator Performance Overview</h3>
            </div>
            <div class="box-body">
                @if($creators->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Creator</th>
                                    <th>Videos</th>
                                    <th>Orders</th>
                                    <th>Wallet Balance</th>
                                    <th>Pending Balance</th>
                                    <th>Total Earned</th>
                                    <th>Total Paid Out</th>
                                    <th>Join Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($creators as $creator)
                                <tr>
                                    <td>
                                        <strong>{{ $creator->name }}</strong><br>
                                        <small>{{ $creator->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue">{{ $creator->videos_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-green">{{ $creator->orders_count }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($creator->wallet->balance ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-warning">${{ number_format($creator->wallet->pending_balance ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-info">${{ number_format($creator->wallet->total_earned ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-primary">${{ number_format($creator->wallet->total_paid_out ?? 0, 2) }}</strong>
                                    </td>
                                    <td>{{ $creator->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-muted">No creators found.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
