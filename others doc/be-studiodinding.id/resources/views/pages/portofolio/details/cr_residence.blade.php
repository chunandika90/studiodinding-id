@extends('layouts')
@section('css')
@endsection

@section('body_content')
    {{--
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/img/portfolio/cr-residence-01.jpg') }}" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100">CR Residence</h4>
      </div>

    </section><!-- /Hero Section -->
    --}}
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4 mt-4 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/cr-residence-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Rumah BSD Anggrek Loka</h5> -->
            
          </div>
          <h1 data-aos="fade-up" data-aos-delay="100" style="color: #eee; margin-top:50px;"><center>CR Residence</center></h1>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <table width="100%" class="table">
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Location</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">Puri Botanical, Jakarta Barat</td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Land Size</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">360 m<sup>2</sup></td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Project Year</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">2025</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <p style="font-size: 20px;">
               The residence is defined by its introverted layout, where the house breathes from within rather than relying solely on the perimeter.
            </p>
            
            <p style="font-size: 20px;"> The architect has orchestrated a layout that circulates air from the inside out, utilizing a series of interconnected courtyards that function as the home’s primary circulatory and atmospheric organs.
            </p>
          </div>
          
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/cr-residence-02.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/cr-residence-03-a.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
            
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            
              <img src="{{ asset('assets/img/portfolio/cr-residence-03-b.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/cr-residence-04.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-05.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/cr-residence-07a.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
            
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            
              <img src="{{ asset('assets/img/portfolio/cr-residence-07b.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-06.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-08.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-09.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-10.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-11.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-12.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/cr-residence-13.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
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