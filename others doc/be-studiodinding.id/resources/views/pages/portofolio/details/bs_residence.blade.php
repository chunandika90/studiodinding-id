@extends('layouts')
@section('body_content')
    {{--
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/img/portfolio/bs-residence-01.jpg') }}" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100">BS Residence</h4>
      </div>

    </section><!-- /Hero Section -->
    --}}
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        <div class="row gy-4 mt-4 mb-5">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/bs-residence-07.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Rumah BSD Anggrek Loka</h5> -->
          </div>
        </div>
        
        <div class="row gy-4 mt-2 mb-5">
          <h1 data-aos="fade-up" data-aos-delay="100" style="color: #eee; margin-top:50px;"><center>BS Residence</center></h1>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <table width="100%" class="table">
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Location</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">Pondok Indah, Jakarta Selatan</td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Land Size</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">300 m<sup>2</sup></td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Project Year</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">2024</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <p style="font-size: 20px;">
              BS House is located in Pondok Indah, South Jakarta. <br>
              This 3-story house is divided into several zoning areas : the ground floor as the service area, the second floor as the public area, and the third floor as the private area.
              Emphasizing a modern tropical design, this house incorporates natural materials such as travertine marble, wood, and concrete. The façade features a sloping travertine frame, complemented by a spacious canopy and wooden lattice for effective shading.
            </p>
            
            <p style="font-size: 20px;">A central inner courtyard, positioned between the living and dining areas, serves as a focal point, bringing freshness and a natural ambiance to the home. The double-height living room, located between the inner courtyard and the balcony, exudes spaciousness while ensuring optimal lighting and ventilation, seamlessly blending with the outdoor area. The abundance of greenery featured throughout the façade, entrance, balcony, and inner courtyard enhances the overall vitality of the home.
            Moreover, the incorporation of curved elements in the interior details lends a dynamic quality to the spaces, while the choice of materials fosters a neutral and natural atmosphere.
            </p>
          </div>
        </div>
        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/bs-residence-02a.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
            
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            
              <img src="{{ asset('assets/img/portfolio/bs-residence-02b.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/bs-residence-04.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/bs-residence-05.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            
              <img src="{{ asset('assets/img/portfolio/bs-residence-03a.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
            
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            
              <img src="{{ asset('assets/img/portfolio/bs-residence-03b.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
            
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/bs-residence-08.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/bs-residence-09.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
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