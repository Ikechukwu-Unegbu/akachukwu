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
<div class="prose mx-auto p-4">
    <h1 class="text-2xl font-bold">Vastel Refund Policy</h1>
    <p class="text-gray-700">October 2024</p>
    <p>At Vastel, we strive to ensure that all transactions made on our Platform are processed seamlessly. However, we understand that there may be circumstances where a refund is necessary. This Refund Policy outlines the conditions under which users can request refunds and how they will be handled.</p>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">1. Eligibility for Refunds</h2>
    <p>Refunds may be issued under the following circumstances:</p>
    <ul class="list-disc list-inside">
        <li><strong>Failed Transactions:</strong> If a transaction (e.g., airtime purchase, bill payment) is not successfully completed and the amount is deducted from your wallet, you may be eligible for a refund.</li>
        <li><strong>Incomplete Transactions:</strong> If a service is partially fulfilled (e.g., a partial airtime recharge like processing or hanging transactions), we will process a refund for the unfulfilled amount.</li>
        <li><strong>System Errors:</strong> Refunds may be processed if there are system errors that prevent successful completion of transactions.</li>
    </ul>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">2. Non-Eligible Transactions</h2>
    <p>Refunds are not available for:</p>
    <ul class="list-disc list-inside">
        <li><strong>User Errors:</strong> Mistakes made by the user (e.g., incorrect phone number or biller account information) are not eligible for refunds. Please double-check your inputs before confirming transactions.</li>
        <li><strong>Wallet Withdrawals:</strong> Customers cannot withdraw funds from their Vastel wallet to a bank account, as Vastel is not licensed for direct cash-out services at this time.</li>
    </ul>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">3. Refund Request Process</h2>
    <p>To request a refund:</p>
    <ul class="list-disc list-inside">
        <li>Contact our customer support team at <a href="mailto:support@vastel.io" class="text-blue-600 underline">support@vastel.io</a> or via the Live chat button on the app or website with the transaction details (e.g., username, transaction ID, date, and amount).</li>
        <li>Our support team will review your request and provide a response within 48 hours.</li>
        <li>If the refund request is approved, the refund will be processed within 72 hours and credited back to your Vastel wallet.</li>
    </ul>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">4. Refund Method</h2>
    <p>All approved refunds will be credited back to your Vastel wallet for future use. Refunds cannot be transferred back to your bank account or any third-party platform.</p>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">5. Dispute Resolution for Refunds</h2>
    <p>If you are dissatisfied with the outcome of your refund request, you may escalate the matter to our support management team.</p>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">6. Privacy References</h2>
    <p>Any personal information collected as part of the refund process will be handled in accordance with our <a href="{{route('privacy')}}"> Privacy Policy</a>. For more information on how we protect your data, please review the <a href="{{route('privacy')}}"> Privacy Policy</a>.</p>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">7. Changes to the Refund Policy</h2>
    <p>We reserve the right to update or modify this Refund Policy at any time. Any changes will be communicated via the Platform or through your registered email.</p>

    <h2 class="text-xl font-bold mb-3 mt-[3rem]">8. Contact Information</h2>
    <p>For any questions or concerns regarding refunds, please contact us at:</p>
    <ul class="list-disc list-inside">
        <li>Email: <a href="mailto:support@vastel.io" class="text-blue-600 underline">support@vastel.io</a></li>
    </ul>
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