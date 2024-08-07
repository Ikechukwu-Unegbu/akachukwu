@extends('layouts.new-guest')

@section('seo')

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vastel is your go-to app for seamless VTU top-ups and neo banking services in Nigeria. Experience the future of banking with hassle-free transactions, instant airtime top-ups, and more.">
    <meta name="keywords" content="Vastel, VTU top-up, neo bank, Nigeria, airtime top-up, banking app, financial services, mobile payments, utility payments, neo banking">
    <meta name="author" content="Vastel">

    <meta name="robots" content="index, follow">

     <!-- Canonical URL -->
     <link rel="canonical" href="https://www.vastel.io">

    <!-- Open Graph Meta Tags for Facebook -->
    <meta property="og:title" content="Vastel - Nigerian VTU Top-Up and Neo Bank App">
    <meta property="og:description" content="Experience effortless VTU top-ups and modern banking services with Vastel. Your reliable partner for mobile payments, airtime top-ups, and banking needs in Nigeria.">
    <meta property="og:image" content="https://www.vastel.io/images/og-image.jpg">
    <meta property="og:url" content="https://www.vastel.io">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vastel">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Vastel - Nigerian VTU Top-Up and Neo Bank App">
    <meta name="twitter:description" content="Vastel offers convenient VTU top-ups and modern banking solutions in Nigeria. Enjoy seamless transactions and top-notch financial services with our user-friendly app.">
    <meta name="twitter:image" content="https://www.vastel.io/images/twitter-image.jpg">
    <meta name="twitter:url" content="https://www.vastel.io">
    <meta name="twitter:site" content="@Vastel">
    <meta name="twitter:creator" content="@Vastel">
    <title>VasTel Nig</title>
@endsection 


@section('body')

      <section class="pt-7">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-6 text-md-start text-center py-6">
              <h1 class="mb-4 fs-9 fw-bold">Unlock Convenience with Every Click</h1>
              <p class="mb-6 lead text-secondary">
                Welcome to VasTel, your one-stop destination for seamless access to airtime, internet data, online result checking, cable TV subscriptions, and more. Experience the ease of managing your essential services with just a few taps. Join us and simplify your digital lifestyle today
              </p>
              <div class="text-center text-md-start">
                @guest
                  <a class="btn vastel-bg me-3 btn-lg" href="{{route('login')}}" role="button">Login</a>               
                @endguest
                @auth
                  <a class="btn vastel-bg me-3 btn-lg" href="{{ auth()->user()->dashboard() }}" role="button">Dashboard</a>
                @endauth
                <a class="btn btn-link vastel-test fw-medium" href="#!" role="button" data-bs-toggle="modal" data-bs-target="#popupVideo"><span class="fas fa-play me-2"></span>Watch the video </a></div>
            </div>
            <div class="col-md-6 text-end"><img class="pt-7 pt-md-0 img-fluid" src="{{asset('pub-pages\assets\img\hero\hero-img.png')}}" alt="" /></div>
          </div>
        </div>
      </section>


      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="pt-5 pt-md-9 mb-6" id="feature">

        <div class="bg-holder z-index--1 bottom-0 d-none d-lg-block" style="background-image:url(pub-pages\assets\img\category\shape.png);opacity:.5;">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <h1 class="fs-9 fw-bold mb-4 text-center"> Elevate Your Digital Experience with  <br class="d-none d-xl-block" />VasTel</h1>
          <div class="row">
            <div class="col-lg-3 col-sm-6 mb-2"> <img class="mb-3 ms-n3" src="{{asset('pub-pages\assets\img\category\icon1.png')}}" width="75" alt="Feature" />
              <h4 class="mb-3">24/7 Availability</h4>
              <p class="mb-0 fw-medium text-secondary">Access services anytime, anywhere, 24/7.</p>
            </div>
            <div class="col-lg-3 col-sm-6 mb-2"> <img class="mb-3 ms-n3" src="{{asset('pub-pages\assets\img\category\icon2.png')}}" width="75" alt="Feature" />
              <h4 class="mb-3">Secure Transactions</h4>
              <p class="mb-0 fw-medium text-secondary">Trust in secure transactions for peace of mind.</p>
            </div>
            <div class="col-lg-3 col-sm-6 mb-2"> <img class="mb-3 ms-n3" src="{{asset('pub-pages\assets\img\category\icon3.png')}}" width="75" alt="Feature" />
              <h4 class="mb-3">Customer Support</h4>
              <p class="mb-0 fw-medium text-secondary">Prompt assistance whenever you need it.</p>
            </div>
            <div class="col-lg-3 col-sm-6 mb-2"> <img class="mb-3 ms-n3" src="{{asset('pub-pages\assets\img\category\icon4.png')}}" width="75" alt="Feature" />
              <h4 class="mb-3">User-Friendly Interface</h4>
              <p class="mb-0 fw-medium text-secondary">Navigate easily for a seamless experience.</p>
            </div>
          </div>
          <div class="text-center"><a class="btn vastel-bg" href="/login" role="button">Register</a></div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="pt-5" id="validation">

        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <h5 class="text-secondary">Airtime and Data</h5>
              <h2 class="mb-2 fs-7 fw-bold">Internet</h2>
              <p class="mb-4 fw-medium text-secondary">
                In addition to providing reliable internet services, we also offer airtime top-up for major networks in Nigeria, ensuring you stay connected whenever and wherever you are
              </p>
              <h4 class="fs-1 fw-bold">Cheapest Data Plans</h4>
              <p class="mb-4 fw-medium text-secondary">Explore our budget-friendly data plans! Enjoy unbeatable rates on top networks in Nigeria. Stay connected affordably with our selection. Browse, stream, and connect without compromise!</p>
              <h4 class="fs-1 fw-bold">Instant Top-up</h4>
              <p class="mb-4 fw-medium text-secondary">Experience lightning-fast airtime top-up! With our seamless service, recharge your phone instantly and stay connected without delay. Say goodbye to waiting and hello to instant connectivity. Top-up hassle-free, anytime, anywhere.</p>
              
            </div>
            <div class="col-lg-6"><img class="img-fluid" src="{{asset('pub-pages\assets\img\validation\validation.png')}}" alt="" /></div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="pt-5" id="manager">

        <div class="container">
          <div class="row">
            <div class="col-lg-6"><img class="img-fluid" src="{{asset('pub-pages\assets\img\manager\manager.png')}}" alt="" /></div>
            <div class="col-lg-6">
              <h5 class="text-secondary">Education Services</h5>
              <p class="fs-7 fw-bold mb-2">Result Checking</p>
              <p class="mb-4 fw-medium text-secondary">
               
              </p>
              <div class="d-flex align-items-center mb-3"> <img class="me-sm-4 me-2" src="{{asset('pub-pages\assets\img\manager\tick.png')}}" width="35" alt="tick" />
                <p class="fw-medium mb-0 text-secondary">Gain immediate access to your exam results for WAEC, JAMB, and NECO exams without delay.</p>
              </div>
              <div class="d-flex align-items-center mb-3"> <img class="me-sm-4 me-2" src="{{asset('pub-pages\assets\img\manager\tick.png')}}" width="35" alt="tick" />
                <p class="fw-medium mb-0 text-secondary">Simply enter your details and get your results quickly, saving time and hassle.</p>
              </div>
              <div class="d-flex align-items-center mb-3"><img class="me-sm-4 me-2" src="{{asset('pub-pages\assets\img\manager\tick.png')}}" width="35" alt="tick" />
                <p class="fw-medium mb-0 text-secondary">Trust our platform for accurate and secure result checking, ensuring your privacy and peace of mind.</p>
              </div>
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="pt-5" id="marketer">

        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <h5 class="text-secondary">Electricity and Cable TV</h5>
              <p class="mb-2 fs-8 fw-bold">Utilities</p>
              <p class="mb-4 fw-medium text-secondary">
                From electricity to cable TV subscriptions, our platform simplifies bill payments. Say goodbye to long queues and late fees. Enjoy seamless transactions and stay connected hassle-free!
              </p>
              <h4 class="fw-bold fs-1">Convenience Redefined</h4>
              <p class="mb-4 fw-medium text-secondary">Skip the queues and pay utility bills from anywhere. Enjoy hassle-free transactions, saving time and effort.</p>
              <h4 class="fw-bold fs-1">One-Stop Solution</h4>
              <p class="mb-4 fw-medium text-secondary"> Manage all utility payments in one place. Simplify your finances with our user-friendly platform</p>
              {{-- <h4 class="fw-bold fs-1">Custom Design designers</h4>
              <p class="mb-4 fw-medium text-secondary">If you are looking for a new way to promote your business<br />that won't cost you more money,</p> --}}
            </div>
            <div class="col-lg-6"><img class="img-fluid" src="{{asset('pub-pages\assets\img\marketer\marketer.png')}}" alt="" /></div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="py-md-11 py-8" id="superhero">

        <div class="bg-holder z-index--1 bottom-0 d-none d-lg-block background-position-top" style="background-image:url(pub-pages/assets/img/superhero/oval.png);opacity:.5; background-position: top !important ;">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
              <h1 class="fw-bold mb-4 fs-7">Do you need support? We are here to help</h1>
              <p class="mb-5 text-info fw-medium">If you have any questions, concerns, or need support, don't hesitate to reach out to us. Click the 'Contact Us' button below, and our friendly customer support team will be happy to assist you. Your satisfaction is our priority</p>
              <button type="button" data-bs-toggle="modal" data-bs-target="#contactModal" class="btn vastel-bg btn-md">Contact our expert</button>
              {{-- @include('components._contact_us') --}}
               @livewire('component.global.contact-us')
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="pt-5" id="marketing">

        <div class="container">
          <h1 class="fw-bold fs-6 mb-3">We are Trusted by hundreds of 1000s </h1>
          <p class="mb-6 text-secondary">Join 200,000+ other netizens and purchase your utilities at best rates</p>
          <div class="row">
            <div class="col-md-4 mb-4">
              <div class="card"><img class="card-img-top" src="assets/img/marketing/marketing01.png" alt="" />
                <div class="card-body ps-0">
                  {{-- <p class="text-secondary">By <a class="fw-bold text-decoration-none me-1" href="#">Abdullah</a>|<span class="ms-1">03 March 2019</span></p> --}}
                  <h5 class="fw-bold">Over 200,000 returning users</h5>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card"><img class="card-img-top" src="assets/img/marketing/marketing02.png" alt="" />
                <div class="card-body ps-0">
                  {{-- <p class="text-secondary">By <a class="fw-bold text-decoration-none me-1" href="#">Abdullah</a>|<span class="ms-1">03 March 2019</span></p> --}}
                  <h5 class="fw-bold">Over 30,000 Daily Transaction</h5>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card"><img class="card-img-top" src="assets/img/marketing/marketing03.png" alt="" />
                <div class="card-body ps-0">
                  {{-- <p class="text-secondary">By <a class="fw-bold text-decoration-none me-1" href="#">Abdullah</a>|<span class="ms-1">03 March 2019</span></p> --}}
                  <h5 class="fw-bold">Affordable, Trusted and Reliable</h5>
                </div>
              </div>
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->



    @include('components.footer')
@endsection 