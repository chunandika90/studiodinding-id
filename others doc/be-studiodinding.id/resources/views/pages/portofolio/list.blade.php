@extends('layouts')
@section('body_content')
    <!-- Hero Section -->
    <section id="" class="section dark-background">

      <!-- <img src="assets/img/home-bg.jpg" alt="" data-aos="fade-in"> -->
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" style="margin-top: 40px;" data-aos-delay="100">PORTFOLIO</h4>
        <br>
        <p data-aos="fade-up"><a class="porto-menu readMore font_title" onclick="featured()">FEATURED</a> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; <a class="porto-menu readMore font_title" onclick="residential()">RESIDENTIAL</a> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; <a class="porto-menu readMore font_title" onclick="commercial()">COMMERCIAL</a></p>
      </div>

    </section><!-- /Hero Section -->
    
    <!-- About Section -->
    <section id="about" class="about section portofolio_area">
      <div class="container" data-aos="fade-up">
        <div class="row gy-4 mb-5 portfolio-type residential-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/ry-house">
              <img src="{{ asset('assets/img/portfolio/ry-house-01.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">RY House</h5>
            </a>
          </div>
        </div>
        
        <div class="row gy-4 mt-5 mb-5 portfolio-type residential-type">
          <div class="col-lg-6">
            <a href="/portfolio/details/da-house">
              <img src="{{ asset('assets/img/portfolio/da-house-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">DA House</h5>
            </a>
          </div>
          <div class="col-lg-6">
            <a href="/portfolio/details/wo-residence">
              <img src="{{ asset('assets/img/portfolio/wo-residence-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">WO Residence</h5>
            </a>
          </div>

        </div>

        <div class="row gy-4 mb-5 portfolio-type residential-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/ws-residence">
              <img src="{{ asset('assets/img/portfolio/ws-residence-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">WS Residence</h5>
            </a>
          </div>
        </div>
        <div class="row gy-4 mb-5 portfolio-type commercial-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/beauty-haul">
              <img src="{{ asset('assets/img/portfolio/beauty-haul-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">Beauty Haul</h5>
            </a>
          </div>
        </div>
        <div class="row gy-4 mb-5 portfolio-type residential-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/villa-bag">
              <img src="{{ asset('assets/img/portfolio/villa-bag-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">Villa Bag</h5>
            </a>
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5 portfolio-type residential-type">
          <div class="col-lg-6">
            <a href="/portfolio/details/co-house">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">CO House</h5>
            </a>
          </div>
          <div class="col-lg-6">
            <a href="/portfolio/details/k_house">
              <img src="{{ asset('assets/img/portfolio/K-HOUSE-0.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">K House</h5>
            </a>
          </div>
        </div>

        <div class="row gy-4 mb-5 portfolio-type commercial-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/hotel-milenium">
              <img src="{{ asset('assets/img/portfolio/hotel-milenium-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">Hotel Milenium</h5>
            </a>
          </div>
        </div>

        <div class="row gy-4 mb-5 portfolio-type residential-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/bsd-anggrek-loka">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">BSD Anggrek Loka</h5>
            </a>
          </div>
        </div>

        <div class="row gy-4 mb-5 portfolio-type residential-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/bs-residence">
              <img src="{{ asset('assets/img/portfolio/bs-residence-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">BS Residence</h5>
            </a>
          </div>
        </div>

        <div class="row gy-4 mb-5 portfolio-type residential-type">
          <div class="col-lg-12">
            <a href="/portfolio/details/cr-residence">
              <img src="{{ asset('assets/img/portfolio/cr-residence-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <h5 class="text-center">CR Residence</h5>
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
    <script>
        function featured() {
            $('.portfolio-type').hide();
            $('.portfolio-type').fadeIn('slow');
        }

        function residential() {
            $('.portfolio-type').hide();
            $('.residential-type').fadeIn('slow');
        }
        
        function commercial() {
            $('.portfolio-type').hide();
            $('.commercial-type').fadeIn('slow');
        }
    </script>
@endsection