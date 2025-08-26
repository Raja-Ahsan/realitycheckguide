@extends('layouts.admin.app')

@section('title', 'Analytics')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Analytics</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Creator Dashboard</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar"></i> Video Performance
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($videoPerformance->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Video Title</th>
                                                <th>Views</th>
                                                <th>Purchases</th>
                                                <th>Conversion Rate</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($videoPerformance as $video)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('videos.show', $video) }}">
                                                            {{ Str::limit($video->title, 30) }}
                                                        </a>
                                                        @if($video->is_intro)
                                                            <span class="badge badge-info">Intro</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($video->views_count) }}</td>
                                                    <td>{{ number_format($video->purchases_count) }}</td>
                                                    <td>{{ $video->conversion_rate }}%</td>
                                                    <td>${{ number_format($video->revenue, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No video performance data available yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar"></i> Date Range
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('creator.analytics') }}">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ $startDate }}">
                                </div>
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ $endDate }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Apply Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
