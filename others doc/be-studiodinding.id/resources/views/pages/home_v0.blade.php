@extends('layouts')
@section('body_content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

    <!-- <img src="{{ asset('assets/img/home3-bg.jpg') }}" alt="" data-aos="fade-in"> -->
    <video autoplay muted loop data-aos="fade-in">
        <source src="https://butikdev.oss-ap-southeast-5.aliyuncs.com/temporary/mim_dawn.mp4" type="video/mp4">
        <!-- <source src="{{ asset('assets/video/mim_dawn.mp4') }}" type="video/mp4"> -->
    </video>

    <div class="container d-flex flex-column align-items-center header-content">
        <h4 data-aos="fade-up" data-aos-delay="100">WE CREATE SPACE FOR YOU</h4>
        <p data-aos="fade-up" data-aos-delay="200">Good design is about helping clients meet their needs and objectives because every space has its own problem.</p>
    </div>

    </section><!-- /Hero Section -->
@endsection

@section('scripts')
@endsection