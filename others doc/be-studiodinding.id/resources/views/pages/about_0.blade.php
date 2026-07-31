@extends('layouts')
@section('body_content')

    
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container_outside_0">
        <div class="container" data-aos="fade-up">

          <div class="row gy-4 mt-5 mb-5">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 desc-project" style="text-align: center;" data-aos="fade-up" data-aos-delay="250">
              <!-- <p><h3 style="color: aliceblue;">WHO WE ARE</h3></p> -->
              <p style="font-size: 25px;">
                <!-- <span style="background: #eee; color:dimgray;">Studio Dinding is a multi-disciplinary team based in Jakarta, Indonesia. Studio Dinding offers total design solutions, architectural, interior, lighting, furnitures and building construction needs – which gives us advantages to deliver seamless design solution</span> -->
                <i>Studio Dinding is a multi-disciplinary team based in Jakarta, Indonesia. Studio Dinding offers total design solutions, architectural, interior, lighting, furnitures and building construction needs – which gives us advantages to deliver seamless design solution</i>
              </p>
            </div>
            
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <img src="{{ asset('assets/img/portfolio/bg-about.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
            </div>
            <div class="col-lg-6 desc-project" data-aos="fade-up" data-aos-delay="250">
              <div style="border-left: 1px solid #d5d5d592; margin-left: 20px; padding-left: 20px; padding-right: 20px;">
                <p style="font-size: 14px; line-height: 2em; color: #d5d5d592;">We continually strive to enrich people's lives through providing creative, comfortable, energy efficient and fresh solution as we strongly believe Studio Dinding's work seeks to be timeless, unique and personal. </p>
                <p style="font-size: 14px; line-height: 2em; color: #d5d5d592;">Studio Dinding  is a highly regarded studio known for its considered, client-centric approach—crafting tailored design solutions from concept through to completion.</p>
                <p style="font-size: 14px; line-height: 2em; color: #d5d5d592;"><i>“Our mission is to deliver outcomes that surpass expectation—designs that are both distinctive and purposeful. We create spaces that authentically reflect our clients or their brand, while elevating the way they live and interact within them.”</i></p>
                
                <p style="font-size: 14px; color: #d5d5d592; line-height: 2em;">Studio Dinding is passionate about all facets of design and believes it’s an essential component to delivering a comprehensive and successful project. The focus is always on creating spaces that are sophisticated, refined and genuinely reflecting a client’s personality or brand. We believe in a collaborative work, let us work with you, not for you.</p>
                <p style="font-size: 14px; color: #d5d5d592;">Studio Dinding has spent more than 15 years curating a diverse portfolio spanning high end residential, retail, hospitality and corporate projects.</p>
              </div>
            </div>
          </div>

          <div class="row mt-5 mb-5 container_outside">
            <div class="col-lg-12 col-md-12 col-sm-12 desc-project" data-aos="fade-up" data-aos-delay="250" style="text-align: center;">
              <p><h3 style="color: aliceblue;">OUR TEAM</h3></p>
              
              <p style="font-size: 25px;">
                <i>Underpinned by a highly collaborative design approach and different professional backgrounds, Studio Dinding leadership team is passionate about all facets of design and believes it’s an essential component to delivering a comprehensive and successful project.</i>
              </p>
              <hr>
            </div>
            <div class="col-lg-12 text-left mt-5" data-aos="fade-up" data-aos-delay="100">
              <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 desktopProfile">
                    <h1 class="nameProfile">RYAN DHARMANSYAH</h1>
                    <p style="font-size: 25px; color: #fff;">Co-Founder</p>
                    <p style="font-size: 14px; color: #d5d5d592; line-height: 2em;">Ryan born in Indonesia and raised in Sydney, Australia. Ryan graduated in architecture from University of New South Wales with Japanese architeture practice during his exchangestudies. He has been working as an architect for 4 years in Sydney and went on to found his own practice specializing in innovative architecture and green building. <div style="margin-top:20px;"><a id="ryanReadMore" class="readMore" style="cursor: pointer;" onclick="ryanProfile()">READ MORE</a></div></p>

                    <p class="ryanProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Ryan believes the spaces we occupy shape how we behave. This has consequences for our      psychological well-being and creative performance. Given that many of us spend years in their residences and workplaces, it makes sense to organize and optimize that space in the most beneficial ways possible. His design vision is to strive energy efficient houses, healthy and comfortable space.</p>

                    <p class="ryanProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Ryan is the Founding Principal & Design Director responsible for for all design direction, liaising with suppliers and project delivery onsite. Ryan has undertaken a wide spectrum of work, ranging from commercial and residential projects.</p>

                    <p class="ryanProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">In his free time, Ryan enjoys photography and travelling in search of new experiences and beautiful landscape.</p>
                </div>
                <div class="col-lg-1 col-sm-12"></div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <img src="{{ asset('assets/img/people-1.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
                </div>

                <div class="col-lg-7 col-md-7 col-sm-12 mobileProfile">
                    <h1 class="nameProfile">RYAN DHARMANSYAH</h1>
                    <p style="font-size: 25px; color: #fff;">Co-Founder</p>
                    <p style="font-size: 14px; color: #d5d5d592; line-height: 2em; ">Ryan born in Indonesia and raised in Sydney, Australia. Ryan graduated in architecture from University of New South Wales with Japanese architeture practice during his exchangestudies. He has been working as an architect for 4 years in Sydney and went on to found his own practice specializing in innovative architecture and green building. <div style="margin-top:20px;"><a id="ryanReadMore2" class="readMore" style="cursor: pointer;" onclick="ryanProfile()">READ MORE</a></div></p>

                    <p class="ryanProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Ryan believes the spaces we occupy shape how we behave. This has consequences for our      psychological well-being and creative performance. Given that many of us spend years in their residences and workplaces, it makes sense to organize and optimize that space in the most beneficial ways possible. His design vision is to strive energy efficient houses, healthy and comfortable space.</p>

                    <p class="ryanProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Ryan is the Founding Principal & Design Director responsible for for all design direction, liaising with suppliers and project delivery onsite. Ryan has undertaken a wide spectrum of work, ranging from commercial and residential projects.</p>

                    <p class="ryanProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">In his free time, Ryan enjoys photography and travelling in search of new experiences and beautiful landscape.</p>
                </div>



                <div class="col-sm-12" style="padding-top: 50px; padding-bottom: 50px;"><hr style="border-bottom: 1px solid #eee;"></div>
              </div>
              <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 desktopProfile">
                    <h1 class="nameProfile">MELITA LUMANTO</h1>
                    <p style="font-size: 25px; color: #fff;">Co-Founder & Creative Director</p>
                    <p style="font-size: 14px; color: #d5d5d592; line-height: 2em;">Melita graduated from Swinburne University of Technology in Melbourne with a degree in Interior Design. She was awarded Master of Lighting Design at University of Technology Sydney in 2017. <div style="margin-top:20px;"><a id="melitaReadMore" class="readMore" style="cursor: pointer;" onclick="melitaProfile()">READ MORE</a></div></p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita trained as an interior architect, but quickly found a passion for lighting design. Her interior and lighting design background is what stands her above others. She brings an enhanced understanding of light that influences major architectural features into form, proportion, materiality, colours and texture.</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita graduated from Swinburne University of Technology in Melbourne with a degree in Interior Design. She was awarded Master of Lighting Design at University of Technology Sydney in 2017.</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita has a passion for design, and believes that each project has its unique needs and qualities from which innovative and fresh solutions are required. Every good design is personalised and the result of good communication with clients.</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita has been involved in a variety of projects in both the private and public sectors including residential,commercial, office, retail, and hospitality. Her previous work experience covers collaborations with the worlds leading designers and architects; across sectors of work from high end hospitality, such as Raffles City Retail Kiosk (Singapore), Crown Hotel Barangaroo (Sydney). Melita is also a member of HDII (Himpunan Designer Interior Indonesia).</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Outside the studio, Melita enjoys travelling and is a keen basketball player and singer enthusiast.</p>
                </div>
                <div class="col-lg-1 col-sm-12"></div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <img src="{{ asset('assets/img/people-2.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 mobileProfile">
                    <h1 class="nameProfile">MELITA LUMANTO</h1>
                    <p style="font-size: 25px; color: #fff;">Co-Founder & Creative Director</p>
                    <p style="font-size: 14px; color: #d5d5d592; line-height: 2em;">Melita graduated from Swinburne University of Technology in Melbourne with a degree in Interior Design. She was awarded Master of Lighting Design at University of Technology Sydney in 2017. <div style="margin-top:20px;"><a id="melitaReadMore2" class="readMore" style="cursor: pointer;" onclick="melitaProfile()">READ MORE</a></div></p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita trained as an interior architect, but quickly found a passion for lighting design. Her interior and lighting design background is what stands her above others. She brings an enhanced understanding of light that influences major architectural features into form, proportion, materiality, colours and texture.</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita graduated from Swinburne University of Technology in Melbourne with a degree in Interior Design. She was awarded Master of Lighting Design at University of Technology Sydney in 2017.</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita has a passion for design, and believes that each project has its unique needs and qualities from which innovative and fresh solutions are required. Every good design is personalised and the result of good communication with clients.</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Melita has been involved in a variety of projects in both the private and public sectors including residential,commercial, office, retail, and hospitality. Her previous work experience covers collaborations with the worlds leading designers and architects; across sectors of work from high end hospitality, such as Raffles City Retail Kiosk (Singapore), Crown Hotel Barangaroo (Sydney). Melita is also a member of HDII (Himpunan Designer Interior Indonesia).</p>
                    <p class="melitaProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Outside the studio, Melita enjoys travelling and is a keen basketball player and singer enthusiast.</p>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-12" style="padding-top: 50px; padding-bottom: 50px;"><hr style="border-bottom: 1px solid #eee;"></div>
              </div>
              <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 desktopProfile">
                    <h1 class="nameProfile">HENRY CHANDRA</h1>
                    <p style="font-size: 25px; color: #fff;">Construction Manager</p>
                    <p style="font-size: 14px; color: #d5d5d592; line-height: 2em;">Henry was awarded a degree in Civil Engineering at University of New South Wales, Sydney, Australia. He then went back to Jakarta, Indonesia and started his career as a junior quantity surveyor. Quickly after that, he gained knowledge about the construction items in the project and started to assist the procurement team. <div style="margin-top:20px;"><a id="henryReadMore" class="readMore" style="cursor: pointer;" onclick="henryProfile()">READ MORE</a></div></p>
                    <p class="henryProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">He was promoted to take on bigger role to supervise the project which exposed him to every part of the operation to round off his skills. After a year, Henry was entrusted to lead the team and manage projects for the company. Henry strongly believes that the biggest room in the world is the room for self improvement.There is never enough when it comes to improving oneself. The process of constant personal growth means a journey, it is not a destination. His dream is to be a leader, inspired and be inspired by developing a generation of leaders.</p>
                    <p class="henryProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">As Construction Manager, Henry is responsible in planning, budgeting, and the execution of the projects on site. His positive and never give-up attitude, leads him to strive to deliver best practices that will ensure the works are executed within the required timeframe.</p>
                    <p class="henryProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Henry is currently leading the Music Ministry in his church community. He enjoys playing music, singing, and sports activities (basketball, futsal, and running) in his leisure.</p>
                </div>
                <div class="col-lg-1 col-sm-12"></div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <img src="{{ asset('assets/img/people-3.jpg') }}" class="img-fluid rounded-1 mb-2 w-100" alt="">
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 mobileProfile">
                    <h1 class="nameProfile">HENRY CHANDRA</h1>
                    <p style="font-size: 25px; color: #fff;">Construction Manager</p>
                    <p style="font-size: 14px; color: #d5d5d592; line-height: 2em;">Henry was awarded a degree in Civil Engineering at University of New South Wales, Sydney, Australia. He then went back to Jakarta, Indonesia and started his career as a junior quantity surveyor. Quickly after that, he gained knowledge about the construction items in the project and started to assist the procurement team. <div style="margin-top:20px;"><a id="henryReadMore2" class="readMore" style="cursor: pointer;" onclick="henryProfile()">READ MORE</a></div></p>
                    <p class="henryProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">He was promoted to take on bigger role to supervise the project which exposed him to every part of the operation to round off his skills. After a year, Henry was entrusted to lead the team and manage projects for the company. Henry strongly believes that the biggest room in the world is the room for self improvement.There is never enough when it comes to improving oneself. The process of constant personal growth means a journey, it is not a destination. His dream is to be a leader, inspired and be inspired by developing a generation of leaders.</p>
                    <p class="henryProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">As Construction Manager, Henry is responsible in planning, budgeting, and the execution of the projects on site. His positive and never give-up attitude, leads him to strive to deliver best practices that will ensure the works are executed within the required timeframe.</p>
                    <p class="henryProfile" style="font-size: 14px; color: #d5d5d592; line-height: 2em; display: none;">Henry is currently leading the Music Ministry in his church community. He enjoys playing music, singing, and sports activities (basketball, futsal, and running) in his leisure.</p>
                </div>
                <div class="col-sm-1"></div><div class="col-sm-12" style="padding-top: 50px; padding-bottom: 50px;"><hr style="border-bottom: 1px solid #eee;"></div>
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
  <script>
    function ryanProfile() {
      $('#ryanReadMore').hide();
      $('#ryanReadMore2').hide();
      $('.ryanProfile').fadeIn();
    }
    
    function melitaProfile() {
      $('#melitaReadMore').hide();
      $('#melitaReadMore2').hide();
      $('.melitaProfile').fadeIn();
    }
    
    function henryProfile() {
      $('#henryReadMore').hide();
      $('#henryReadMore2').hide();
      $('.henryProfile').fadeIn();
    }
  </script>
@endsection