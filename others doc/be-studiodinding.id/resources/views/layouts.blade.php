<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Studio Dinding</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon-sd.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
  <!-- Open Graph untuk sosial media / Google rich result -->
<meta property="og:image" content="{{ asset('assets/img/favicon-sd.png') }}">
<meta property="og:image:type" content="image/png">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

  <style>
    #logoMain {
      /* width: 140px !important;  */
      height: 77px !important;
      min-height: 77px !important;
      /* border: 1px solid #f00 !important; */
      object-fit: contain; /* atau cover */
    }
    .header-content {
      background-color: rgba(0, 0, 0, 0.4); padding: 20px;
    }

    .prpl-10 {
      padding: 0 20px;
    }

    section, .section {
      background-color: #000000;
    }

    section h5 {
      color: antiquewhite;
    }

    body {
      background-color: #000000;
    }

    footer {
      background-color: #14191a !important;
      color: bisque !important;
    }

    .h3-desc {
      color: bisque !important;
      line-height: 50px;
      letter-spacing: 3px;;
    }

    .p-desc {
      color: whitesmoke;
    }

    .desc-project {
      color: whitesmoke;
    }

    .dark-background-div {
      background-color: #14191a !important;
      color: bisque !important;
    }

    .dark-background-div h3 {
      color: bisque !important;
    }

    .dark-background-div input, .dark-background-div textarea, .dark-background-div select {
      background-color: #14191a !important;
      border: 1px solid #424242 !important;
      color: bisque !important;
    }

    .dark-background-div input::placeholder, .dark-background-div textarea::placeholder {
      color: #757575 !important;
    }

    .dark-background-div button {
      background-color: #000 !important;
      border: 1px solid #424242 !important;
      color: bisque !important;
    }
    .header.scrolled {
      background-color: #000; /* atau warna solid lain */
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .porto-menu :hover {
      font-weight: bold !important;
      text-decoration: underline !important;
    }
    
    .readMore{
      position: relative;
      text-decoration: none;
      font-size: 20px;
      color: #999;
      cursor: pointer;
    }

    .font_title {
      font-size: 20px;
    }

    .readMore::after {
      content: "";
      position: absolute;
      width: 0;
      height: 2px;
      left: 0;
      bottom: -4px;
      background: #fff;
      transition: width 0.3s ease;
    }

    .readMore:hover::after {
      width: 100%;
    }

  .mobile_sidebar {
      font-family: 'Roboto', sans-serif !important;
      position: fixed;
      top: 75px;
      right : 20px;
      width: 200px;
      height: 175px; /* penuh setinggi layar */
      background: #eee;
      color: #555;
      padding: 20px;
      z-index: 999;
      text-align: left;
      display: none;
      font-size: 20px;
      border-radius: 20px;
    }
  
  .mobile_sidebar div {
    padding-bottom: 5px;
    border-bottom: 0px solid #757575;
    margin-top: 10px;
  }

  .mobile_sidebar h2 {
    margin-top: 50px;
    margin-bottom: 20px;
    color: #444;
    font-size: 25px;
    display: none;
  }

  .mobile_sidebar .buttonclose {
    color: #555;
    font-weight: bold;
  }

  .mobile_sidebar a {
    color: #424242;
  }

  .main-menu-01 {
    display: block;
  }

  .button_menu_mobile {
    border: 0px solid #f00; 
    margin-top: -20px; 
    padding-right: 40px; 
    font-size: 20px; 
    z-index:9;
    display: none;
  }

  .portofolio_area {
    margin-top: -50px;;
  }

  .desktopProfile {
    display: block;
  }

  .mobileProfile {
    display: none;
  }
  .container_outside {
    margin-left: 150px;
    margin-right: 150px;
    border: 0px solid #f00;
  }

  .nameProfile {
    color: #fff; 
    font-size: 50px;
  }

  table {
    --bs-table-border-color: #888 !important;
  }


  @media (max-width: 996px) {
    .mobileProfile {
      display: block;
    }
    .desktopProfile {
      display: none;
    }
    .container_outside {
      margin-left: auto;
      margin-right: auto;
      border: 0px solid #f00;;
    }
  }
  @media (max-width: 1199px) {
    .main-menu-01 {
      display: none;
    }
    .button_menu_mobile {
      display: block;
    }
  }
  @media (max-width: 750px) {
  .nameProfile {
    font-size: 37px;
  }
    .portofolio_area {
      margin-top: -80px;;
    }
  }
  @media (max-width: 560px) {
    .font_title {
      font-size: 15px;
    }
  }

  @media (max-width: 430px) {
    .font_title {
      font-size: 13px;
    }
  }
  </style>
  @yield('head')

  <!-- =======================================================
  * Template Name: Dewi
  * Template URL: https://bootstrapmade.com/dewi-free-multi-purpose-html-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a id="logoMainArea" href="/" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img id="logoMain" src="{{ asset('assets/img/sd-logo-1.png') }}?v={{ date('ymdhis') }}" alt="studio dinding logo"> -->
        <!-- <h1 class="sitename">Studio Dinding</h1> -->
      </a>

      <nav id="navmenu" class="navmenu main-menu-01">
        <ul>
          <li><a href="/portfolio">Portfolio</a></li>
          <li><a href="/about">About</a></li>
          <!-- <li style="display: none;"><a href="#services">News</a></li> -->
          <!-- <li><a href="#team">Team</a></li> -->
          <!-- <li style="display: none;" class="dropdown"><a href="#"><span>Media</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> -->
          <li><a href="/contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <!-- <a class="cta-btn" href="index.html#about">Get Started</a> -->

    </div>
    <div class="button_menu_mobile"><b style="cursor: pointer;">MENU</b></div>
  </header>

  <main class="main">
    @yield('body_content')
  <!-- mobile sidebar -->
  <div class="mobile_sidebar">
    <!-- <a style="cursor: pointer; color:#999; font-size: 17px;" class="closebutton">Close</a> -->
    <h2>Menu</h2>
    <div><a href="/portfolio">PORTFOLIO</a></div>
    <div><a href="/about">ABOUT</a></div>
    <div><a href="/contact">CONTACT</a></div>
  </div>

  <!-- Whatsapp Logo -->
  <div style="position: fixed; width: 50px; height: 50px; bottom:60px; right:10px; z-index:9999; color: #189d0e; font-size: 20px; font-weight: bold;">
    <a href="https://wa.me/6281289795996?text=Hello%20Studio%20Dinding%20i'm%20interested%20in%20using%20your%20service" target="_blank">
      <img src="{{ asset('assets/img/logo55666.png') }}" width="100%" alt="studio dinding WA chat">
    </a>
  </div>

  <!-- Instagram Logo -->
  <div style="position: fixed; width: 50px; height: 50px; bottom:120px; right:10px; z-index:9999; color: #189d0e; font-size: 20px; font-weight: bold;">
    <a href="https://www.instagram.com/studio.dinding" target="_blank">
      <img src="{{ asset('assets/img/instagram.png') }}" width="100%" alt="studio dinding instagram">
    </a>
  </div>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script> -->
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <!-- izitoast -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    window.addEventListener('scroll', function () {
      const navbar = document.getElementById('header');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
    

  </script>

  <script>
    $.ajax({
      url: "/getLogo",   // file atau API tujuan
      type: "GET",       // method GET
      data: {
        logo: "main"
      },
      cache: false,      // cegah cache
      success: function(response) {
        $('#logoMainArea').html(response);
      },
      error: function(xhr, status, error) {
        console.error("Error:", error);
      }
    });
  </script>
  
  <script type="text/javascript">
      @if (Session::has('success'))
          iziToast.success({
              title: 'OK',
              position: 'bottomRight',
              timeout: 5000,
              message: "{{ Session::get('success') }}",
          });
      @endif
      @if (Session::has('oops'))
          iziToast.error({
              title: '',
              position: 'bottomRight',
              timeout: 5000,
              message: "{{ Session::get('oops') }}",
          });
      @endif

      $('.button_menu_mobile').click(function() {
        $('.mobile_sidebar').fadeToggle(500);
      });

      $('.closebutton').click(function() {
        $('.mobile_sidebar').fadeOut();
      });
  </script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  @yield('scripts')
</body>

</html>