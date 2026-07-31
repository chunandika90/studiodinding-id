@extends('layouts')
@section('body_content')
    {{--
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/img/portfolio/beauty-haul-01.jpg') }}" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100"></h4>
      </div>

    </section><!-- /Hero Section -->
    --}}
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4 mt-4 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-02.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Rumah BSD Anggrek Loka</h5> -->
            
          </div>
        </div>
        <div class="row gy-4 mt-2 mb-5">
          <h1 data-aos="fade-up" data-aos-delay="100" style="color: #eee; margin-top:50px;"><center>Beauty Haul</center></h1>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <table width="100%" class="table">
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Location</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">Margo City Mall, Depok</td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Land Size</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">170 sqm</td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Project Year</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">2022</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <p style="font-size: 20px;">
               Studiodinding design atomnpunk & retrofuturistic theme skincare store - colourful display stands panctuate a stark calm background at the  first flagship store of beautyhaul Characterised by a feature "halo" lighting that highlight the circular shape of the store. A bulky column was also camouflaged to good use as display storage and a center piece covered by blue painted ripple stainless steel that creates a visually stunning impact.
            </p>
            
            <p style="font-size: 20px;">Combining retro atomnpunk with a touch of kitsch concept for a playful approaches on colours, forms & materials. Various custom design  such as blue coloured ripplestainless steel used to cover the column, mirror ceiling to make the space looks bigger& brighter , gondola  display inspired by jukebox, halo ceiling, textured glass and many others playful shapes & different pattern is used. This union infuses the 60s  eras to th space, which implies with so many different colours, forms & materials everyone is perfectly unique & beautiful in their own way
            </p>
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-03.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
            
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-04.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-05.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-06.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="120">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-07.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
            
          </div>
        </div>
        
        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-08.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
            
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="130">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-09.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="140">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-10.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="150">
            
              <img src="{{ asset('assets/img/portfolio/beauty-haul-11.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="160">
              <img src="{{ asset('assets/img/portfolio/beauty-haul-12.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
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