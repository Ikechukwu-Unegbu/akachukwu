@extends('layouts.new-guest')

@section('body')

      <section class="pt-7">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-6 text-md-start text-center py-6">
              <h1 class="mb-4 fs-9 fw-bold">Unlock Convenience with Every Click</h1>
              <p class="mb-6 lead text-secondary">
                Welcome to [Platform Name], your one-stop destination for seamless access to airtime, internet data, online result checking, cable TV subscriptions, and more. Experience the ease of managing your essential services with just a few taps. Join us and simplify your digital lifestyle today
              </p>
              <div class="text-center text-md-start"><a class="btn btn-warning me-3 btn-lg" href="{{route('login')}}" role="button">Get started</a><a class="btn btn-link text-warning fw-medium" href="#!" role="button" data-bs-toggle="modal" data-bs-target="#popupVideo"><span class="fas fa-play me-2"></span>Watch the video </a></div>
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
          <h1 class="fs-9 fw-bold mb-4 text-center"> Elevate Your Digital Experience with  <br class="d-none d-xl-block" />[Platform Name]</h1>
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
          <div class="text-center"><a class="btn btn-warning" href="/login" role="button">SIGN UP NOW</a></div>
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
              <button class="btn btn-warning btn-md">Contact our expert</button>
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
          <h1 class="fw-bold fs-6 mb-3">Marketing Strategies</h1>
          <p class="mb-6 text-secondary">Join 40,000+ other marketers and get proven strategies on email marketing</p>
          <div class="row">
            <div class="col-md-4 mb-4">
              <div class="card"><img class="card-img-top" src="assets/img/marketing/marketing01.png" alt="" />
                <div class="card-body ps-0">
                  <p class="text-secondary">By <a class="fw-bold text-decoration-none me-1" href="#">Abdullah</a>|<span class="ms-1">03 March 2019</span></p>
                  <h3 class="fw-bold">Increasing Prosperity With Positive Thinking</h3>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card"><img class="card-img-top" src="assets/img/marketing/marketing02.png" alt="" />
                <div class="card-body ps-0">
                  <p class="text-secondary">By <a class="fw-bold text-decoration-none me-1" href="#">Abdullah</a>|<span class="ms-1">03 March 2019</span></p>
                  <h3 class="fw-bold">Motivation Is The First Step To Success</h3>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card"><img class="card-img-top" src="assets/img/marketing/marketing03.png" alt="" />
                <div class="card-body ps-0">
                  <p class="text-secondary">By <a class="fw-bold text-decoration-none me-1" href="#">Abdullah</a>|<span class="ms-1">03 March 2019</span></p>
                  <h3 class="fw-bold">Success Steps For Your Personal Or Business Life</h3>
                </div>
              </div>
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="pb-2 pb-lg-5">

        <div class="container">
          <div class="row border-top border-top-secondary pt-7">
            <div class="col-lg-3 col-md-6 mb-4 mb-md-6 mb-lg-0 mb-sm-2 order-1 order-md-1 order-lg-1"><img class="mb-4" src="{{asset('pub-pages/assets/img/logo.svg')}}" width="184" alt="" /></div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 order-3 order-md-3 order-lg-2">
              <p class="fs-2 mb-lg-4">Quick Links</p>
              <ul class="list-unstyled mb-0">
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">About us</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Blog</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Contact</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">FAQ</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 order-4 order-md-4 order-lg-3">
              <p class="fs-2 mb-lg-4">Legal stuff</p>
              <ul class="list-unstyled mb-0">
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Disclaimer</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Financing</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Privacy Policy</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Terms of Service</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-4 mb-lg-0 order-2 order-md-2 order-lg-4">
              <p class="fs-2 mb-lg-4">
                knowing you're always on the best energy deal.</p>
              <form class="mb-3">
                <input class="form-control" type="email" placeholder="Enter your phone Number" aria-label="phone" />
              </form>
              <button class="btn btn-warning fw-medium py-1">Sign up Now</button>
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="text-center py-0">

        <div class="container">
          <div class="container border-top py-3">
            <div class="row justify-content-between">
              <div class="col-12 col-md-auto mb-1 mb-md-0">
                <p class="mb-0">&copy; 2022 Your Company Inc </p>
              </div>
              <div class="col-12 col-md-auto">
                <p class="mb-0">
                  Made with<span class="fas fa-heart mx-1 text-danger"> </span>by <a class="text-decoration-none ms-1" href="https://themewagon.com/" target="_blank">ThemeWagon</a></p>
              </div>
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->
@endsection 