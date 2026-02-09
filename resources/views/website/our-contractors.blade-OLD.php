@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

<!-- ======= Hero Section ======= -->
<section id="inner-hero" class="d-flex justify-content-center align-items-center">
    <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
        <div class="carousel-item active">
            <div class="carousel-container">
                <h2 class="animate__animated animate__fadeInDown">Our Contractors</h2>
            </div>
        </div>
    </div>
</section>
<!-- End Hero -->

<!-- Fetch Top Rated Contractors -->
<?php
    $topRated = App\Models\User::whereHas('roles', function ($q) {
        $q->where('name', 'Contractor');
    })
    ->where('status', 1)
    ->where('top_rated', 1)
    ->whereHas('payment', function($q) {
        $q->where('payment_status', 'succeeded'); // Payment succeeded logic
    })
    ->get();
?>

<!-- Conditional rendering for Top Rated Contractors -->
@if($topRated->isNotEmpty())
<!-- agents section -->
<section class="agents-section">
    <div class="container">
        <div class="agents-h text-center">
            <h1>Our Top Rated Contractors</h1>
        </div>
        <div class="row pt-3">
            @foreach($topRated as $agent)
            <div class="col-md-3">
                <div class="agent1">
                    <div class="agent1-img">
                        <a href="{{ url('contractor-detail/'.$agent->id) }}">
                            @if($agent->image)
                            <img src="{{ asset('admin/assets/images/UserImage/'.$agent->image) }}">
                            @else
                            <img src="{{ asset('admin/assets/images/Agents/no-photo1.jpg') }}" style="height: 300px">
                            @endif
                        </a>
                        <div class="agent1-icons">
                            <a href="{{ $agent->facebook }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="{{ $agent->twitter }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            <a href="{{ $agent->instagram }}"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            <a href="{{ $agent->behance }}"><i class="fa fa-behance" aria-hidden="true"></i></a>
                            <a href="{{ $agent->youtube }}"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="agent1-name text-center pt-2">
                        <h5>{{ $agent->name }} {{ $agent->last_name }}</h5>
                        <h6>{{ $agent->designation }}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End agents section -->

@else

<!-- All Contractors Section -->
<?php
$allContractors = App\Models\User::whereHas('roles', function ($q) {
    $q->where('name', 'Contractor');
})
->where('status', 1)
->where('top_rated', 0)
/* ->whereHas('payment', function($q) {
    $q->where('payment_status', 'succeeded'); // Payment succeeded logic
}) */
->get();
?>
<section class="agents-section">
    <div class="container">
        <div class="agents-h text-center">
            <h1>All Contractors</h1>
        </div>
        <div class="row pt-4">
            @foreach($allContractors as $agent)
            <div class="col-md-3">
                <div class="agent1">
                    <div class="agent2-img">
                        <a href="{{ url('contractor-detail/'.$agent->id) }}">
                            @if($agent->image)
                            <img src="{{ asset('admin/assets/images/UserImage/'.$agent->image) }}">
                            @else
                            <img src="{{ asset('admin/assets/images/Agents/no-photo1.jpg') }}" style="height: 392px">
                            @endif
                        </a>
                        <div class="agent1-icons">
                            <a href="{{ $agent->facebook }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="{{ $agent->twitter }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            <a href="{{ $agent->instagram }}"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            <a href="{{ $agent->behance }}"><i class="fa fa-behance" aria-hidden="true"></i></a>
                            <a href="{{ $agent->youtube }}"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="agent1-name text-center pt-2">
                        <h5>{{ $agent->name }} {{ $agent->last_name }}</h5>
                        <h6>{{ $agent->designation }}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End All Contractors Section -->

@endif

@endsection
