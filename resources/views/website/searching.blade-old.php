@extends('layouts.website.master')
@section('title', $page_title)
@section('content')
    <!-- Thank you  section -->
    <section class="thank-you-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="thank-you">
                        <h1>Searching Properties!</h1>
                        <p>We received your query regarding this property <br> We will contact you soon</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="for-rent">
        <div class="container">
            <div class="row">
                @foreach ($search_result as $result )
                    <div class="col-md-4 pb-3">
                        <div class="rental-box">
                            <div class="pack-image-box"> <img src="{{ asset('admin/assets/images/property_thumbnails') }}/{{ $result->image }}" alt="">
                                <div class="rent-header">
                                    <div class="rent-featured">
                                        @if ($result->is_featured == 1)
                                            <span> Featured </span>
                                        @endif
                                    </div>
                                    <div class="rent-offers">
                                        @if ($result->property_type == 2)
                                            <span>Sales</span>
                                        @else
                                            <span>Rentals</span>
                                        @endif
                                        <span class="offer">New Offer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="pack-rent-content">
                                <h3>{{$result->name}} With Subunits <br><span>from $ {{$result->price}}</span></h3>
                                <p>{!!\Illuminate\Support\Str::limit($result->short_description,100)!!}</p>
                            </div>
                            <div class="rent-foot"> <a href="{{ url ('listing-detail').'/'.$result->slug }}" class="uni-btn">Read More</a> </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <tr>
                <td colspan="6">
                    <div class="d-flex justify-content-center">
                        {!! $search_result->links('pagination::bootstrap-4') !!}
                    </div>
                </td>
            </tr>
        </div>
       </section>
    <!-- Thank you  section -->
@endsection
