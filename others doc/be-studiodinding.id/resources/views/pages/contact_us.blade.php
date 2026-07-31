@extends('layouts')
@section('head')
  <!-- <link href="{{ asset('assets/css/main_home.css') }}" rel="stylesheet"> -->
@endsection
@section('body_content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="assets/img/portfolio/home-03-contact.jpg" alt="" data-aos="fade-in">
      <!-- <video autoplay muted loop data-aos="fade-in">
        <source src="assets/video/mim_dawn.mp4" type="video/mp4">
      </video> -->

      <div class="container d-flex flex-column align-items-center">
        <div style="text-align: center !important;">
          <!-- <h2 style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">Prioritizing quality over quantity and seemless execution</h2>
          <p style="font-family: 'Roboto', sans-serif !important; text-align: left !important;">It offers services from concept to site supervision</p> -->
          
          <h2 style="font-family: 'Roboto', sans-serif !important; text-align: center !important;">Contact Us</h2>
          <p style="font-family: 'Roboto', sans-serif !important; text-align: center !important;">Get in touch with us</p>
          
          <!-- Section Title -->
          <!-- <div class="container section-title" data-aos="fade-up">
            <h2 style="color: antiquewhite !important;">Contact</h2>
            <p style="color: antiquewhite !important;"></p>
          </div> -->
          <!-- End Section Title -->
        </div>
      </div>

    </section><!-- /Hero Section -->
    
    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2 style="color: antiquewhite !important;">Contact</h2>
        <p style="color: antiquewhite !important;">Get in touch with us</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 ">
            <div class="row gy-4">

              <div class="col-lg-12">
                <a href="https://maps.app.goo.gl/HXEbqPnbBJVxESkT7" target="_blank">
                  <div class="info-item d-flex flex-column justify-content-center align-items-center dark-background-div" data-aos="fade-up" data-aos-delay="200">
                    <i class="bi bi-geo-alt"></i>
                    <h3>Address</h3>
                    <p>Jl. Tanjung Duren Barat IV No.22A, RT.11/RW.6,</p>
                    <p>Tj. Duren Utara, Kec. Grogol Petamburan</p>
                    <p>Kota Jakarta Barat</p>
                    <p>Daerah Khusus Ibukota Jakarta 11470</p>
                  </div>
                </a>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <a href="https://wa.me/6281289795996?text=Hello%20Studio%20Dindin%20I'm%20Interested%20in%20the%20Product">
                  <div class="info-item d-flex flex-column justify-content-center align-items-center dark-background-div" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-telephone"></i>
                    <h3>Call Us</h3>
                    <p>+62 812-8979-5996</p>
                  </div>
                </a>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center dark-background-div" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Us</h3>
                  <p>studiodinding@gmail.com</p>
                </div>
              </div><!-- End Info Item -->

            </div>
          </div>

          <div class="col-lg-6">
            <form action="/contact/submit" method="post" class="php-email-form dark-background-div" data-aos="fade-up" data-aos-delay="500">
              <div class="row gy-4">
                {!! csrf_field() !!}
                <div class="col-md-12">
                    <select name="contact_type" class="form-control" required="">
                        <option value="" disabled selected>Contact Type</option>
                        <option value="project">Project </option>
                        <option value="collaboration">Collaboration </option>
                        <option value="hiring_job">Hiring / Job</option>
                    </select>
                </div>
                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="4" placeholder="Message" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Send Message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

    <footer id="footer" class="footer">

    <div id="formsc" class="container copyright text-center mt-4">
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