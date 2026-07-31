@extends('layouts')
@section('body_content')

    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-3"></div>
          <div class="col-lg-6 desc-project" style="text-align: center;" data-aos="fade-up" data-aos-delay="250">
            <p><h3 style="color: aliceblue;">Who we are</h3></p>
            <p style="font-size: 20px;"><span style="background: #eee; color:dimgray;">Studio Dinding is a multi-disciplinary team based in Jakarta, Indonesia. Studio Dinding offers total design solutions, architectural, interior, lighting, furnitures and building construction needs – which gives us advantages to deliver seamless design solution</span></p>
          </div>
          <div class="col-lg-4 desc-project" data-aos="fade-up" data-aos-delay="250">
            <p style="font-size: 20px;">We continually strive to enrich people's lives through providing creative, comfortable, energy efficient and fresh solution as we strongly believe Studio Dinding's work seeks to be timeless, unique and personal. </p>
            <p style="font-size: 16px; line-height: 2em; color: #ccc;">Studio Dinding  is a highly regarded studio known for its considered, client-centric approach—crafting tailored design solutions from concept through to completion.</p>
            <p style="font-size: 16px; line-height: 2em; color: #ccc;"><i>“Our mission is to deliver outcomes that surpass expectation—designs that are both distinctive and purposeful. We create spaces that authentically reflect our clients or their brand, while elevating the way they live and interact within them.”</i></p>
            <p style="font-size: 20px;">You are part of the team, let us work with you, not for you.</p>
          </div>
          <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('assets/img/team.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
        </div>

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-7 col-md-4 col-sm-12" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-5 col-md-8 col-sm-12 desc-project" data-aos="fade-up" data-aos-delay="250" style="text-align: right;">
            <p><h3 style="color: aliceblue;">Our Team <small style="font-weight: normal; font-size: 14px; color:darkgray">profile</small></h3></p>
            <p>Studio Dinding is a team of young creative-driven individuals<br>
with different professional backgrounds which gives us<br>
advantages to deliver total design solutions -<br>
architectural, interior, lighting, furnitures and building construction needs</p>
          </div>
          <div class="col-lg-12 text-left" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="{{ url('about/Ryan-Dharmansyah') }}">
                  <img src="{{ asset('assets/img/people-1.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
                  <h5>Ryan Dharmansyah<br><small style="font-size: 15px;">Co-Founder</small></h5>
                  <p>Ryan born in Indonesia and raised in Sydney, Australia. Ryan graduated in architecture from University of New South Wales with Japanese architeture practice during his exchangestudies. He has been working as an architect for 4 years in Sydney and we...<b>Read More</b></p>
                </a>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="{{ url('about/Melita-Lumanto') }}">
                  <img src="{{ asset('assets/img/people-2.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
                  <h5>Melita Lumanto<br><small style="font-size: 15px;">Co-Founder & Creative Director</small></h5>
                  <p>Melita graduated from Swinburne University of Technology in Melbourne with a degree in Interior Design. She was awarded Master of Lighting Design at University of Technology Sydney in 2017. Melita trained as an interior architect, but quickly found a pa...<b>Read More</b></p>
                </a>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="{{ url('about/Henry-Chandra') }}">
                  <img src="{{ asset('assets/img/people-3.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
                  <h5>Henry Chandra<br><small style="font-size: 15px;">Construction Manager</small></h5>
                  <p>Henry was awarded a degree in Civil Engineering at University of New South Wales, Sydney, Australia. He then went back to Jakarta, Indonesia and started his career as a junior quantity surveyor. Quickly after that, he gained knowledge about the constru...<b>Read More</b></p>
                </a>
              </div>
            </div>
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