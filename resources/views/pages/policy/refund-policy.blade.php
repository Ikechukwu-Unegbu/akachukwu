@extends('layouts.new-guest')

@section('body')

    <section class="pt-5 container" id="marketing">
        <h1 class="container text-center">Welcome to VasPay Refund Policy Page</h1>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Privacy Policy
                </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                        Thank you for shopping at Vaspay.

This is the Return and Refund <a href="{{route('privacy')}}">Policy of Vaspay.</a>
                </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    Digital Products
                </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    We do not issue refunds for digital products once the order is confirmed and the product is sent.

We recommend contacting us for assistance if you experience any issues receiving or downloading our products.
                </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    Contact us
                </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    If you have any questions about our Returns and Refunds Policy, please contact us:

By phone number - 08039451810

By email -info@vaspay.ng
                </div>
                </div>
            </div>

        
        </div>
    </section>






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
    



      @include('components.footer')
@endsection 