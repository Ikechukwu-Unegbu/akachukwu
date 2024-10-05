@extends('layouts.new-ui')


@section('seo')

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vastel is your go-to app for seamless VTU top-ups and neo banking services in Nigeria. Experience the future of banking with hassle-free transactions, instant airtime top-ups, and more.">
    <meta name="keywords" content="Vastel, VTU top-up, neo bank, Nigeria, airtime top-up, banking app, financial services, mobile payments, utility payments, neo banking">
    <meta name="author" content="Vastel">

    <meta name="robots" content="index, follow">

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
    <title>VasTel Nig | Refund Policy</title>
@endsection 


@section('body')
@include('components.menu-navigation')

<section class="pt-5 container mx-auto" id="marketing">
  <h1 class="text-4xl font-bold text-center mb-8 text-gray-900">Welcome to VasTel Refund Policy Page</h1>
  
  <div id="accordionFlushExample" class="max-w-3xl mx-auto">
    <!-- Accordion item 1 -->
    <div class="border-b border-gray-200">
      <h2 id="flush-collapseOne">
        <button class="w-full py-4 px-5 text-lg text-left flex justify-between items-center font-medium text-gray-800 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-vastel_blue" 
          type="button" data-target="#collapseOne">
          Privacy Policy
          <svg class="w-5 h-5 transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
      </h2>
      <div id="collapseOne" class="hidden" data-body>
        <div class="py-4 px-5 text-gray-700">
          Thank you for using VASTel. This is the Return and Refund <a href="{{ route('privacy') }}" class="text-vastel_blue underline hover:text-vastel_blue">Policy of VasTel</a>.
        </div>
      </div>
    </div>

    <!-- Accordion item 2 -->
    <div class="border-b border-gray-200">
      <h2 id="flush-collapseTwo">
        <button class="w-full py-4 px-5 text-lg text-left flex justify-between items-center font-medium text-gray-800 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-vastel_blue" 
          type="button" data-target="#collapseTwo">
          Digital Products
          <svg class="w-5 h-5 transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
      </h2>
      <div id="collapseTwo" class="hidden" data-body>
        <div class="py-4 px-5 text-gray-700">
          We do not issue refunds for digital products once the order is confirmed and the product is sent. If you experience issues, contact us for assistance.
        </div>
      </div>
    </div>

    <!-- Accordion item 3 -->
    <div class="border-b border-gray-200">
      <h2 id="flush-collapseThree">
        <button class="w-full py-4 px-5 text-lg text-left flex justify-between items-center font-medium text-gray-800 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-vastel_blue" 
          type="button" data-target="#collapseThree">
          Contact us
          <svg class="w-5 h-5 transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
      </h2>
      <div id="collapseThree" class="hidden" data-body>
        <div class="py-4 px-5 text-gray-700">
          If you have any questions about our Refunds Policy, contact us by phone - <span class="font-medium text-gray-800">08039451810</span> or email - <span class="font-medium text-gray-800">info@vastel.io</span>.
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Vanilla JS Accordion
  document.querySelectorAll('[data-target]').forEach(button => {
    button.addEventListener('click', function() {
      const target = document.querySelector(this.getAttribute('data-target'));
      target.classList.toggle('hidden'); // Show/Hide accordion content
      
      // Optional: Toggle arrow rotation
      const icon = this.querySelector('svg');
      icon.classList.toggle('rotate-180');
    });
  });
</script>


<!-- Hero Section -->
<section class="py-20 bg-yellow-50 mt-10" id="superhero">
  <div class="container mx-auto text-center">
    <h1 class="text-4xl font-bold mb-6 text-gray-900">Do you need support? We are here to help</h1>
    <p class="mb-8 text-lg text-gray-600">If you have any questions, concerns, or need support, don't hesitate to reach out to us. Click the 'Contact Us' button below, and our friendly customer support team will be happy to assist you. Your satisfaction is our priority.</p>
    <a href="{{route('settings.support')}}"  class="px-6 py-3 bg-vastel_blue text-white font-medium text-lg rounded-lg shadow-lg hover:bg-vastel_blue transition duration-300">Contact our expert</a>
  </div>


</section>



 @include('components.footer')
@endsection 