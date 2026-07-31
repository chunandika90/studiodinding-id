@extends('layouts')
@section('head')
  <link href="{{ asset('assets/css/main_home.css') }}" rel="stylesheet">
  <style>
    .carousel-control-next, .carousel-control-prev {
        z-index: 99;
    }
  </style>
@endsection
@section('body_content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-item active">
          <img src="{{ asset('assets/img/portfolio/home-01.jpg') }}" alt="">
          <div class="carousel-container" style="text-align: left !important;">
            <h2 style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Every Great design begins here</h2>
            <p style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Timeless design, tailor to your lifestyle and vision</p>
          </div>
        </div><!-- End Carousel Item -->
        
        <div class="carousel-item">
          <img src="{{ asset('assets/img/portfolio/home-02.jpg') }}" alt="">
          <div class="carousel-container" style="text-align: left !important;">
            <h2 style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Prioritizing quality over quantity and seemless execution</h2>
            <p style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">It offers services from concept to site supervision</p>
          </div>
        </div><!-- End Carousel Item -->

        <div class="carousel-item">
          <img src="{{ asset('assets/img/portfolio/home-03.jpg') }}" alt="">
          <div class="carousel-container" style="text-align: left !important;">
            <h2 style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Innovative architecture. Timeless Interiors</h2>
            <p style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Architecture and interiors that balance form, function, and feeling</p>
          </div>
        </div><!-- End Carousel Item -->

        <div class="carousel-item">
          <img src="{{ asset('assets/img/portfolio/home-04.jpg') }}" alt="">
          <div class="carousel-container">
            <h2 style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">We don't just design space, We shape experiences</h2>
            <p style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">From architecture to thoughtful interiors, we create environments that inspire</p>
          </div>
        </div><!-- End Carousel Item -->

        <div class="carousel-item">
          <img src="{{ asset('assets/img/portfolio/home-05.jpg') }}" alt="">
          <div class="carousel-container">
            <h2 style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Every Space Tells a story</h2>
            <p style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Let's craft yours - with architecture and interior that reflects your identity</p>
          </div>
        </div><!-- End Carousel Item -->
        
        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

        <ol class="carousel-indicators">
        </ol>

      </div>

    </section><!-- /Hero Section -->
@endsection

@section('scripts')
@endsection