@extends('layouts')
@section('body_content')
    {{--
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/img/portfolio/da-house-01.jpg') }}" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100">DA House</h4>
      </div>

    </section><!-- /Hero Section -->
    --}}
    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        

        <div class="row gy-4 mt-4 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/da-house-02.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-2 mb-5">
          <h1 data-aos="fade-up" data-aos-delay="100" style="color: #eee; margin-top:50px;"><center>DA House</center></h1>
          <div class="col-lg-3 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" style="text-align: left;" data-aos="fade-up" data-aos-delay="100">
            <table width="100%" class="table">
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Location</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">Green Garden, Jakarta Barat</td>
              </tr>
              <tr>
                <td style="background: #000; color:#f5f5f5; font-size: 20px; font-weight: bold;">Land Size</td>
                <td style="background: #000; color:#aaa; font-size: 20px;">500 sqm</td>
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
              Corner Site always provide an interesting challenge to the designer. The house is positioned strategically in the corner bit in quiet residential block in west Jakarta. The distinctive feature in the design is created by stacking a number of boxes, providing sense of privacy and zoning hierarcy. 
            </p>
            
            <p style="font-size: 20px;">The box in the second level is more open than the top level as it is where the semi private space of the house is. Whereas a more enclosed box in the top part to present the privacy for the inhabitants.
            </p>
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/da-house-03.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
              <!-- <h5 class="text-center">Permata Buana</h5> -->
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <img src="{{ asset('assets/img/portfolio/da-house-04a.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <img src="{{ asset('assets/img/portfolio/da-house-04b.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="110">
              <img src="{{ asset('assets/img/portfolio/da-house-05.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
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