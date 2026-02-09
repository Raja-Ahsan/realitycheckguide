@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

<!-- ======= Hero Section ======= -->
<section id="inner-hero" class="d-flex justify-cntent-center align-items-center">
  <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
    <div class="carousel-item active">
      <div class="carousel-container">
        <h2 class="animate__animated animate__fadeInDown">
          FAQs</h2>
      </div>
    </div>

  </div>
</section><!-- End Hero -->

<!-- ======= Frequently Asked Questions Section ======= -->
<section id="faq" class="faq section-bg">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Frequently Asked Questions</h2>
    </div>

    <div class="faq-list">
      <ul>
        @foreach($faqs as $faq)
        <li data-aos="fade-up" data-aos-delay="{{ 300 + $loop->index * 100 }}">
          <i class="bx bx-help-circle icon-help"></i>
          <a data-bs-toggle="collapse" data-bs-target="#faq-list-{{$faq->id}}" class="collapsed">
            {{$faq->question}}
            <i class="bx bx-chevron-down icon-show"></i>
            <i class="bx bx-chevron-up icon-close"></i>
          </a>
          <div id="faq-list-{{$faq->id}}" class="collapse" data-bs-parent=".faq-list">
            <p>{{$faq->answer}}</p>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</section>
<!-- End Frequently Asked Questions Section -->
</main>
@endsection