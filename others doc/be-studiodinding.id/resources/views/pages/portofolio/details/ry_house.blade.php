@extends('layouts')
@section('body_content')
    {{--
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/img/portfolio/ry-house-01.jpeg') }}" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100">RY House</h4>
      </div>

    </section><!-- /Hero Section -->
    --}}
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        <h4 data-aos="fade-up" data-aos-delay="100" style="color: #eee; margin-top:50px;"><center>RY House</center></h4>

        <!-- <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-7 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <p>The Interior design of this restaurant combines traditional and modern elements that creates a balance between modernity and traditional warmth.</p>
            
            <p>This fusion brings an ambiance that is not only warm but also luxurious, with the play of reflective materials such as bronze elements paired with soft lighting. The shimmering metallic surfaces capture light beautifully, creating a dynamic visual effect and adding depth to the space with an elegant touch.</p>
          </div>
          <div class="col-lg-1 desc-project" style="text-align: center;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-4 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <div data-aos="fade-up" data-aos-delay="200"><small>Location : Green Cove , BSD city</small></div>
            <div data-aos="fade-up" data-aos-delay="200"><small>Land Size : 625 sqm</small></div>
            <div data-aos="fade-up" data-aos-delay="200"><small>Project Year 2024</small></div>
          </div>
        </div> -->
        

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/ry-house-02a.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
            
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            
              <img src="{{ asset('assets/img/portfolio/ry-house-02b.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/ry-house-05.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/ry-house-01.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/ry-house-03.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <img src="{{ asset('assets/img/portfolio/ry-house-04a.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <img src="{{ asset('assets/img/portfolio/ry-house-04b.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/ry-house-06.jpeg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>
      </div>
      <div style="margin-top:20px;"><center><a href="/portfolio" class="readMore" style="cursor: pointer;">ALL PROJECT</a></center></div>
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