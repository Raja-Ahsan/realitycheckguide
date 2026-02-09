@extends('layouts.electrician.app')
@section('title', 'Available Jobs')
@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">Available Jobs</h1>
</section>

<section class="content">
    <style>
        .job-card { border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,.05); transition: box-shadow .2s ease, transform .2s ease; }
        .job-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,.12); transform: translateY(-2px); }
        .job-card-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; border-bottom: 1px solid #f0f2f5; background: #f9fbff; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .job-title { margin: 0; font-size: 18px; font-weight: 600; color: #111827; }
        .chip { background: #eaf2ff; color: #1f5bd8; border-radius: 9999px; padding: 4px 10px; font-size: 12px; font-weight: 600; }
        .job-card-body { padding: 12px 15px; }
        .job-meta { color: #6b7280; margin: 0 0 6px 0; }
        .job-meta i { color: #1f5bd8; margin-right: 6px; }
        .job-actions { padding: 12px 15px; border-top: 1px solid #f0f2f5; display: flex; gap: 8px; align-items: center; }
        .badge-outline { border: 1px solid #d1d5db; color: #374151; border-radius: 9999px; padding: 2px 8px; font-size: 12px; }
        .mb-20 { margin-bottom: 20px; }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Browse Available Jobs</h3>
                </div>
                <div class="box-body">
                    <!-- Search and Filter -->
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('electrician.jobs') }}" class="form-inline" role="search">
                                <div class="row">
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group" style="width:100%; margin-bottom:10px;">
                                            <input type="text" name="search" class="form-control" style="width:100%;" placeholder="Search by title, description, city or state" value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group" style="width:100%; margin-bottom:10px;">
                                            <select name="category" class="form-control" style="width:100%;">
                                                <option value="">All Categories</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group" style="width:100%; margin-bottom:10px;">
                                            <select name="city" class="form-control" style="width:100%;">
                                                <option value="">All Locations</option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                                        {{ $city->city }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group" style="margin-bottom:10px;">
                                            <button type="submit" class="btn btn-primary" style="margin-right:5px;">Search</button>
                                            <a href="{{ route('electrician.jobs') }}" class="btn btn-default">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Jobs List -->
                    @if($jobs->count() > 0)
                        <div class="row" style="margin-bottom: 10px;">
                            @foreach($jobs as $job)
                            <div class="col-md-6">
                                <div class="job-card mb-20">
                                    <div class="job-card-header">
                                            <span class="badge-outline" style="margin-left:8px;"><strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}</span>
                                            <h3 class="job-title">{{ $job->name }}</h3>
                                            <span class="chip">{{ $job->hasCategory->title ?? $job->hasCategory->name ?? 'Uncategorized' }}</span>
                                        </div>
                                        <div class="job-card-body">
                                            <p class="job-meta">
                                                <i class="fa fa-map-marker"></i>
                                                <strong>Location:</strong>
                                                {{ $job->hasCity->city ?? 'N/A' }}, {{ $job->hasState->state ?? 'N/A' }}
                                            </p>
                                            <p class="job-meta">
                                                <i class="fa fa-money"></i>
                                                <strong>Budget:</strong>
                                                ${{ number_format($job->budget_min ?? 0, 2) }} - ${{ number_format($job->budget_max ?? 0, 2) }}
                                            </p>
                                            <p class="job-meta">
                                                <i class="fa fa-user"></i>
                                                <strong>Posted by:</strong> {{ $job->user->name ?? 'N/A' }} {{ $job->user->last_name ?? '' }}
                                                
                                            </p>
                                            <p style="margin:8px 0 0 0; color:#374151;"><strong>Description:</strong> {!! Str::limit($job->description, 160) !!}</p>
                                            @if($job->bids->count() > 0)
                                                <p class="job-meta" style="margin-top:8px;">
                                                    <i class="fa fa-gavel"></i>
                                                    <strong>Bids:</strong> {{ $job->bids->count() }} received
                                                </p>
                                            @endif
                                        </div>
                                        <div class="job-actions">
                                            <a href="{{ route('electrician.job-detail', $job->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-eye"></i> View Details
                                            </a>
                                            @if($job->bids->where('electrician_id', Auth::id())->count() == 0)
                                                <a href="{{ route('electrician.job-detail', $job->id) }}" class="btn btn-success btn-sm">
                                                    <i class="fa fa-gavel"></i> Place Bid
                                                </a>
                                            @else
                                                <span class="label label-warning">Already Bid</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="text-center" style="margin-top:10px;">
                            {{ $jobs->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center">
                            <h3>No jobs available</h3>
                            <p>There are currently no jobs matching your criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 