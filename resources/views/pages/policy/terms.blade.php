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
    <div class="prose max-w-full mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Vastel Terms of Use</h1>
        <p class="text-sm mb-2">October 2024</p>

        <p class="mb-4">Welcome to Vastel!</p>

        <p class="mb-4">
            Thank you for choosing Vastel, a platform that offers digital services such as airtime and data purchases, utility bill payments, education pins, and wallet funding for transactions within the app. These Terms of Use ("Terms") outline the rules and regulations for using our Platform and accessing our services. By using Vastel, you agree to comply with these Terms.
        </p>

        <h2 class="text-2xl font-bold mb-4">1. Description of Services</h2>
        <p class="mb-4">Vastel provides a range of digital services, including but not limited to:</p>
        <ul class="list-disc ml-6 mb-4">
            <li>Airtime and data purchases.</li>
            <li>Utility bill payments (e.g., electricity, water).</li>
            <li>Education pins (e.g., WAEC or JAMB e-pins).</li>
            <li>Wallet funding for seamless transactions within the app.</li>
        </ul>
        <p class="mb-4">
            Additional services may be added as Vastel grows and expands its offerings. Users can access these services through our mobile application and website (collectively, the “Platform”).
        </p>

        <h2 class="text-2xl font-bold mb-4">2. Account Ownership and Security</h2>
        <ul class="list-disc ml-6 mb-4">
            <li><span class="font-semibold">Account Creation:</span> To use Vastel's services, users must create an account by providing their full name, phone number, email address, and a password. Users may also select a unique username.</li>
            <li><span class="font-semibold">PIN Setup:</span> Upon registration, users are required to create a personal identification number (PIN) for confirming transactions.</li>
            <li><span class="font-semibold">Password Recovery:</span> If you forget your password, you can request a reset link via the email address registered with your account.</li>
            <li><span class="font-semibold">Account Security:</span> Users are responsible for maintaining the confidentiality of their account details, including passwords and PINs. If you become aware of any unauthorized access or security breach, please notify us immediately at <a href="mailto:support@vastel.io" class="text-indigo-600">support@vastel.io</a>.</li>
            <li><span class="font-semibold">Third-Party Payment Providers:</span> By making payments on our Platform, you agree to be bound by the terms and conditions of any third-party providers, including bank partners. These providers may have additional requirements for processing payments.</li>
        </ul>

        <h2 class="text-2xl font-bold mb-4">3. User Responsibilities</h2>
        <ul class="list-disc ml-6 mb-4">
            <li>Users must provide accurate and up-to-date information during registration and when using the Platform.</li>
            <li>Users are responsible for complying with all applicable laws and regulations while using the services provided by Vastel.</li>
            <li>Users are expected to use the Platform in good faith and not engage in activities that could harm Vastel or other users.</li>
        </ul>

        <h2 class="text-2xl font-bold mb-4">4. Prohibited Activities</h2>
        <p class="mb-4">You are not allowed to use Vastel for any illegal or unauthorized activities, including but not limited to:</p>
        <ul class="list-disc ml-6 mb-4">
            <li>Fraudulent transactions or money laundering.</li>
            <li>Engaging in activities that violate local, state, or federal laws.</li>
            <li>Tampering with or attempting to gain unauthorized access to the Platform or its infrastructure.</li>
            <li>Misrepresenting your identity or affiliation with any entity.</li>
        </ul>
        <p class="mb-4">Any violation of these prohibited activities may result in the deactivation or restriction of your account.</p>

        <h2 class="text-2xl font-bold mb-4">5. Deactivation of Account</h2>
        <p class="mb-4">
            Vastel reserves the right to deactivate or restrict access to your account at any time, for any reason, without prior notice. Reasons for deactivation may include, but are not limited to:
        </p>
        <ul class="list-disc ml-6 mb-4">
            <li>Violation of these Terms or other policies.</li>
            <li>Suspicious or fraudulent activities detected on your account.</li>
            <li>Non-compliance with applicable laws or regulations.</li>
        </ul>

        <h2 class="text-2xl font-bold mb-4">6. Intellectual Property</h2>
        <p class="mb-4">
            All content on the Vastel Platform, including text, images, logos, and graphics, are the property of Vastel or its licensors. Users are not permitted to copy, distribute, or modify any content without explicit permission. Using Vastel’s intellectual property without authorization may result in legal action.
        </p>

        <h2 class="text-2xl font-bold mb-4">7. Limited Liability</h2>
        <p class="mb-4">
            Vastel strives to provide reliable and uninterrupted services. However, we do not guarantee that our services will be error-free or available at all times. Vastel is not liable for any loss or damage arising from the use or inability to use our Platform. This includes, but is not limited to, loss of data, financial loss, or any other consequential damages.
        </p>

        <h2 class="text-2xl font-bold mb-4">8. Dispute Resolution</h2>
        <p class="mb-4">
            If a dispute arises between a user and Vastel, we encourage users to first contact our support team at <a href="mailto:support@vastel.io" class="text-indigo-600">support@vastel.io</a> to seek resolution.
        </p>

        <h2 class="text-2xl font-bold mb-4">9. Privacy and Data Handling</h2>
        <p class="mb-4">
            By using Vastel, you consent to the collection and use of your information as outlined in our Privacy Policy. Please review the Privacy Policy for details on how we collect, use, and protect your data.
        </p>

        <h2 class="text-2xl font-bold mb-4">10. Changes to the Terms</h2>
        <p class="mb-4">
            Vastel reserves the right to modify these Terms at any time. Any changes will be posted on this page, and continued use of the Platform after such changes constitutes acceptance of the modified Terms.
        </p>

        <h2 class="text-2xl font-bold mb-4">12. Contact Information</h2>
        <p class="mb-4">
            For questions or concerns about these Terms, please contact us at:
        </p>
        <p class="mb-4">
            Email: <a href="mailto:support@vastel.io" class="text-indigo-600">support@vastel.io</a>
        </p>
    </div>

</section>




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