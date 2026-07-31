@extends('layouts')
@section('body_content')

    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        <div class="row gy-4 mt-5 mb-5">
          <div class="col-lg-1 col-md-4 col-sm-12" data-aos="fade-up" data-aos-delay="100"></div>
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('assets/img/people-2.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
          </div>
          <div class="col-lg-6 desc-project" data-aos="fade-up" data-aos-delay="250">
            <p><h3 style="color: aliceblue;">Melita Lumanto</h3></p>
            <p>
              <h5 style="color: aliceblue;">Education
                <small style="font-weight: normal; font-size: 14px; color:#ddd;line-height:20px !important;">
                  <div><b>B.Des - Interior Designer</b> <br><small>Swinburne University of Technology, AU</small></div>
                  <div style="margin-top: 5px;"><b>M.Des - Lighting Designer</b> <br><small>University of Technology Sydney, AU</small></div>
                </small>
              </h5>
            </p>
            
            <p>
              <h5 style="color: aliceblue;">Achievements
                <small style="font-weight: normal; font-size: 14px; color:#ddd;line-height:20px !important;">
                  <br>
                  <table width="100%">
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2012</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Golden Key International Honour Society</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2016</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Academic Excellence Awards</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2016</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Beams Art Festival Sydney Creatives</td>
                    </tr>
                  </table>
                </small>
              </h5>
            </p>
            <h5 style="color: aliceblue;">Profile
              
              <small style="font-weight: normal; font-size: 14px; color:#ddd;line-height:20px !important;">
                <p style="text-align: justify;">Melita graduated from Swinburne University of Technology in Melbourne with a degree in Interior Design. She was awarded Master of Lighting Design at University of Technology Sydney in 2017.</p>
                <p style="text-align: justify;">Melita trained as an interior architect, but quickly found a passion for lighting design. Her interior and lighting design background is what stands her above others. She brings an enhanced understanding of light that influences major architectural features into form, proportion, materiality, colours and texture.
                <p style="text-align: justify;">Melita graduated from Swinburne University of Technology in Melbourne with a degree in Interior Design. She was awarded Master of Lighting Design at University of Technology Sydney in 2017.</p>
                <p style="text-align: justify;">Melita has a passion for design, and believes that each project has its unique needs and qualities from which innovative and fresh solutions are required. Every good design is personalised and the result of good communication with clients.</p>
                <p style="text-align: justify;">Melita has been involved in a variety of projects in both the private and public sectors including residential,commercial, office, retail, and hospitality. Her previous work experience covers collaborations with the worlds leading designers and architects; across sectors of work from high end hospitality, such as Raffles City Retail Kiosk (Singapore), Crown Hotel Barangaroo (Sydney). Melita is also a member of HDII (Himpunan Designer Interior Indonesia).</p>
                <p style="text-align: justify;">Outside the studio, Melita enjoys travelling and is a keen basketball player and singer enthusiast.</p>
                </p>
              </small>
            </h5>
            
            <p>
              <h5 style="color: aliceblue;">Employment
                <small style="font-weight: normal; font-size: 14px; color:#ddd;line-height:20px !important;">
                  <br>
                  <table width="100%">
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2013 - 2014</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Interior Architect</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>Oblique Interiors, Singapore</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2014 - 2016</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Interior Designer</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>Pomeroy Studio, Singapore</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2017</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Lighting Designer</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>PointOfView Design, Australia</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;">2017 - present</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Creative Director & Co-Founder</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>Studio Dinding, Indonesia</td>
                    </tr>
                    <!-- <tr>
                      <td style="width: 100px; border:0px solid #f00;">2017 - present</td>
                      <td style="width: 10px; border:0px solid #f00;">|</td>
                      <td>Associate Architect</td>
                    </tr>
                    <tr>
                      <td style="width: 100px; border:0px solid #f00;"></td>
                      <td style="width: 10px; border:0px solid #f00;"></td>
                      <td>Kunkun 3D Visualisation Partners, Indonesia</td>
                    </tr> -->
                  </table>
                </small>
              </h5>
            </p>
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