@extends('layouts')
@section('body_content')
    <!-- Hero Section -->
    <section id="" class="section dark-background">

      <!-- <img src="assets/img/home-bg.jpg" alt="" data-aos="fade-in"> -->
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" style="margin-top: 40px;" data-aos-delay="100">portfolio</h4>
        <br>
        <p data-aos="fade-up"><a href="/portfolio/commercial" class="porto-menu"><b style="font-size: 20px;">Commercial</b></a> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; <a href="/portfolio/residential" class="porto-menu">Residential</a></p>
        <!-- <p data-aos="fade-up" data-aos-delay="200">Every design is personalised. Finding the right solution to your unique problem is our vision.</p> -->
      </div>

    </section><!-- /Hero Section -->
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        <div class="row gy-4 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
            <a href="/portfolio/details/beauty-haul">
              <img src="{{ asset('assets/img/portfolio/beauty-haul-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">Beauty Haul</h5>
            </a>
          </div>
        </div>

        

        <div class="row gy-4 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
            <a href="/portfolio/details/hotel-milenium">
              <img src="{{ asset('assets/img/portfolio/hotel-milenium-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">Hotel Milenium</h5>
            </a>
          </div>
        </div>
      </div>

    </section><!-- /About Section -->

    <footer id="footer" class="footer">

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">IT Team</strong> <span>All Rights Reserved</span></p>
        <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a href=“https://themewagon.com>ThemeWagon
        </div>
    </div>

    </footer>
@endsection

@section('scripts')
@endsection