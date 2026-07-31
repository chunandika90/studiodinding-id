@extends('layouts')
@section('body_content')
    {{--
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/img/portfolio/WO-HOUSE-02.jpg') }}" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100">WO HOUSE</h4>
      </div>

    </section><!-- /Hero Section -->
    --}}
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        
        <div class="row gy-4 mt-4 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-03.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Rumah BSD Anggrek Loka</h5> -->
          </div>
        </div>
        <div class="row gy-4 mt-2 mb-5">
          <h1 data-aos="fade-up" data-aos-delay="100" style="color: #eee; margin-top:50px;"><center>CO House</center></h1>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <table width="100%" class="table">
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Location</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">PIK, Jakarta Utara</td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Land Size</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">200 sqm</td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Project Year</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">2020</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <p style="font-size: 20px;">
               WO House is a residence of 3.5 stories with Modern Tropical Architecture, situated in long and narrow block on residential complex of North Jakarta. A defining characteristic of this dwelling lies in its inner courtyard, strategically positioned at the heart of the home, bridging the gap between the living and dining area.
            </p>
            
            <p style="font-size: 20px;">The pebble-adorned garden, accentuated by the presence of a Pule tree, seamlessly unites the realms of interior and exterior, fostering an impression of vast, interwoven space. This design not only imparts a refreshing ambiance but also bestows captivating vistas upon the adjoining areas
            </p>
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-04.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-05.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-01.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-06.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="120">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-07.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-08.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kakolait To Go</h5> -->
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-09.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Kims Residence</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="130">
              <img src="{{ asset('assets/img/portfolio/WO-HOUSE-10.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
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