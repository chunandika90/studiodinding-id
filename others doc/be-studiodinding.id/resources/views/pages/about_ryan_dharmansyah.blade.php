@extends('layouts')
@section('body_content')

    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-1 col-md-4 col-sm-12" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-6 desc-project" data-aos="fade-up" data-aos-delay="250">
            <p><h3 style="color: aliceblue;">Ryan Dharmansyah</h3></p>
            <p>
              <h5 style="color: aliceblue;">Education
                <small style="font-weight: normal; font-size: 14px; color:#ddd;line-height:20px !important;">
                  <div><b>B.Arch , M.Arch.</b> <br><small>University of New South Wales, AU</small></div>
                  <div style="margin-top: 5px;"><b>Principal & Chief Architect</b> <br><small>University of New South Wales, AU</small></div>
                </small>
              </h5>
            </p>
            <h5 style="color: aliceblue;">Profile
              <small style="font-weight: normal; font-size: 14px; color:#ddd;line-height:20px !important;">
                <p style="text-align: justify;">Ryan born in Indonesia and raised in Sydney, Australia. Ryan graduated in architecture from University of New South Wales with Japanese architeture practice during his exchangestudies. He has been working as an architect for 4 years in Sydney and went on to found his own practice specializing in innovative architecture and green building. </p>

                <p style="text-align: justify;">Ryan believes the spaces we occupy shape how we behave. This has consequences for our      psychological well-being and creative performance. Given that many of us spend years in their residences and workplaces, it makes sense to organize and optimize that space in the most beneficial ways possible. His design vision is to strive energy efficient houses, healthy and comfortable space.</p>

                <p style="text-align: justify;">Ryan is the Founding Principal & Design Director responsible for for all design direction, liaising with suppliers and project delivery onsite. Ryan has undertaken a wide spectrum of work, ranging from commercial and residential projects.</p>

                <p style="text-align: justify;">In his free time, Ryan enjoys photography and travelling in search of new experiences and beautiful landscape.</p>
              </small>
            </h5>
            
            <p>
              <h5 style="color: aliceblue;">Employment
                <small style="font-weight: normal; font-size: 14px; color:#ddd;line-height:20px !important;">
                  <br>
                  <table width="100%">
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2013 - 2015</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Architect</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>H&D Design Studio Pty Ltd, Australia</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2015 - 2016</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Architect</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>Aleksandar Design Group Pty Ltd, Australia</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2016 - 2017</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Project Architect</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>H&D Design Studio Pty Ltd, Australia</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2017 - present</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Principal Architect</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>Studio Dinding, Indonesia</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2017 - present</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Associate Architect</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>Kunkun 3D Visualisation Partners, Indonesia</td>
                    </tr>
                  </table>
                </small>
              </h5>
            </p>
          </div>
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('assets/img/people-1.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
          <div class="col-lg-1 col-md-4 col-sm-12" data-aos="fade-up" data-aos-delay="100"></div>
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