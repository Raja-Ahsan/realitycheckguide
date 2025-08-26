@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

<!-- ======= Hero Section ======= -->
<section id="inner-hero" class="d-flex justify-cntent-center align-items-center">
  <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
    <div class="carousel-item active">
      <div class="carousel-container">
        <h2 class="animate__animated animate__fadeInDown">Contractor Detail</h2>
      </div>
    </div>
  </div>
</section>
<!-- End Hero -->

    <!--  agetns detail section -->
    <section class="agent-detail">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="agent-main-div">
                        <div class="agent-img-detail img">
                            @if($agent_detail->image)
								<img src="{{ asset('public/admin/assets/images/UserImage/'.$agent_detail->image) }}">
                            @else
                                <img src="{{ asset('public/admin/assets/images/Agents/no-photo1.jpg') }}">
                            @endif
                            <div class="agent-contact-detail">
                                <h4>{{ $agent_detail->name }}</h4>
                                <p>{{ $agent_detail->designation??'--' }}</p>
                                <a href="mailto:{{ $agent_detail->email }}"> <span><i class="fa fa-envelope" aria-hidden="true"></i></span>{{ $agent_detail->email }}</a>
                                <a href="tel:{{ $agent_detail->phone }}"> <span><i class="fa fa-phone" aria-hidden="true"></i></span> {{ $agent_detail->phone }}</a>
                                @if($agent_detail->skype)
                                    <a href="skype:{{ $agent_detail->skype }}?call"> <span><i class="fa fa-skype" aria-hidden="true"></i></span> {{ $agent_detail->skype??'--' }}</a>
                                @else
                                    <a href=""> <span><i class="fa fa-skype" aria-hidden="true"></i></span> {{ $agent_detail->skype??'--' }}</a>
                                @endif
                                <p class="last-p">Member of: SOUTH-CENTRAL VIRGINIA ASSOCIATION</p>
                            </div>
                        </div>
                        <div class="col-md-6 agent-social-link">
                            @if($agent_detail->whatsapp)
                                <a href="https://wa.me/{{ $agent_detail->whatsapp }}"><span><i class="fa fa-whatsapp" aria-hidden="true"></span></i></a>
                            @else
                                <a href=""><span><i class="fa fa-whatsapp" aria-hidden="true"></span></i></a>
                            @endif
                            <a href="{{ $agent_detail->facebook }}"><span><i class="fa fa-facebook" aria-hidden="true"></span></i></a>
                            <a href="{{ $agent_detail->instagram }}"><span><i class="fa fa-instagram" aria-hidden="true"></span></i></a>
                            <a href="{{ $agent_detail->youtube }}"><span><i class="fa fa-youtube-play" aria-hidden="true"></span></i></a>
                        </div>
                        <div class="agent-markting-btn">
                            <!-- <a href="#" class="uni-btn">Send Email</a> -->
                            <a href="tel:{{ $agent_detail->phone }}" class="call-now uni-btn"><span>call<i class="fa fa-phone" aria-hidden="true"></i></span>{{$agent_detail->phone}}</a>
                            @if($agent_detail->whatsapp)
                                <a href="https://wa.me/{{ $agent_detail->whatsapp }}" class="call-now uni-btn"><span><i class="fa fa-whatsapp" aria-hidden="true"></span></i>Whatsapp</a>
                            @else
                                <a href="" class="call-now uni-btn"><span><i class="fa fa-whatsapp" aria-hidden="true"></span></i>Whatsapp</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-me-from">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <h4>Contact Me</h4>
                        <form action="{{ route('contact.store') }}" id="regform" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Your Name</label>
                                    <input type="hidden" class="form-control mb-3" value="{{ $agent_detail->id }}" name="agent_id">
                                    <input type="text" class="form-control mb-3" name="name" id="input1"  required>
                                </div>
                                <div class="col-md-12">
                                    <label for="">Your Email</label>
                                    <input type="email" class="form-control mb-3" name="email" id="input1"  required>
                                </div>
                                <div class="col-md-12">
                                    <label for="">Your Phone</label>
                                    <input type="phone" class="form-control mb-3" name="phone" id="input1" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="">Message</label>
                                    <textarea class="form-control" id="textarea1" cols="30"  name="message"  rows="5"></textarea>
                                </div>
                                <div class="contact-frm-btn">
                                    <button type="submit" class="btn btn-warning btn-cont">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
