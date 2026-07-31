@extends('layouts')
@section('body_content')
    {{--
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-01.jpg') }}" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100">BSD Anggrek Loka</h4>
      </div>

    </section><!-- /Hero Section -->
    --}}
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        <h4 data-aos="fade-up" data-aos-delay="100" style="color: #eee; margin-top:50px;"><center>BSD Anggrek Loka</center></h4>

        <!--         
        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-7 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <p> The client wanted a space that was more like a holiday home, and this area in Puncak is well suited to holiday and retirement life. It`s not far from the city but is still away from the hustle and bustle. The abundance of nature also helps them leave the city behind and enjoy their home and the ample open space. </p>
            <p>on the hill, the site has the most premium view. To the north it has an undistracted vitoward mountain range, so the main idea is to locate all main, living areas and bedroom toward northern part of the site</p>
          </div>
          <div class="col-lg-1 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-4 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <div data-aos="fade-up" data-aos-delay="200"><small>Location : Cisarua, Bogor</small></div>
            <div data-aos="fade-up" data-aos-delay="200"><small>Land Size : 1.4 ha</small></div>
            <div data-aos="fade-up" data-aos-delay="200"><small>Project Year 2021</small></div>
          </div>
        </div>
         -->
        
        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-02.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-03.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>
        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-04.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-07.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>
        
        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-06.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-05.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>
        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-08.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>
        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-09.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>
        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-10.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/bsd-anggrek-loka-11.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
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