<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Vastel Landing Page</title> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
    <link rel="icon" href="{{ asset('images/vastel-icon.png') }}" style="height: 2rem;width:6.94rem;" type="image/png">



    <!-- Primary Meta Tags -->
        <title>Vastel | Airtime, Data, Bill Payments, Transfers & More</title>
        <meta name="title" content="Vastel | Airtime, Data, Bill Payments, Transfers & More">
        <meta name="description" content="Top up airtime, buy data, pay electricity or cable bills, send money, save, and more. Vastel helps you manage daily payments—all in one secure app.">
        <meta name="keywords" content="Vastel, airtime recharge, buy data, pay bills, send money, bill payments Nigeria, wallet top-up, fast payments, secure payments app, electricity bill payment, cable subscription, fintech Nigeria">
        <meta name="author" content="Vastel">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="https://www.vastel.io/" />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://www.vastel.io/">
        <meta property="og:title" content="Vastel | Airtime, Data, Bill Payments, Transfers & More">
        <meta property="og:description" content="Top up airtime, buy data, pay electricity or cable bills, send money, save, and more. Vastel helps you manage daily payments—all in one secure app.">
        <meta property="og:image" content="https://www.vastel.io/assets/images/vastel-og-image.jpg">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="https://www.vastel.io/">
        <meta property="twitter:title" content="Vastel | Airtime, Data, Bill Payments, Transfers & More">
        <meta property="twitter:description" content="Top up airtime, buy data, pay electricity or cable bills, send money, save, and more. Vastel helps you manage daily payments—all in one secure app.">
        <meta property="twitter:image" content="https://www.vastel.io/assets/images/vastel-og-image.jpg">





    <script>window.$zoho=window.$zoho || {};$zoho.salesiq=$zoho.salesiq||{ready:function(){}}</script>
    <script id="zsiqscript" src="https://salesiq.zohopublic.com/widget?wc=siq7e27ed946742b2ef7be4a02f6a2e0772" defer></script>
   
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .floating-label-input:focus + label,
        .floating-label-input:not(:placeholder-shown) + label {
            transform: translateY(-1.5rem);
            font-size: 0.75rem;
            color: #3b82f6;
            display: none;
        }
    </style>
     @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('head')
</head>

<body class="bg-white text-gray-800">
    <!-- Navbar -->
   

    @yield('body')
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
<script>
    function handleFormSubmission(event) {
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true; // Disable the button
        submitButton.textContent = 'Submitting...'; // Optional: Change button text
    }
</script>

<script>
    document.getElementById("triggerZohoChat").addEventListener("click", function() {
        if (typeof $zoho !== "undefined" && $zoho.salesiq) {
            $zoho.salesiq.chat.start();  // Opens the chat window
        } else {
            console.error("Zoho SalesIQ is not loaded");
        }
    });

</script>

<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({
      appId: "5a3d2358-0a87-4274-b9ff-38bedfea92fa",
      safari_web_id: "web.onesignal.auto.1f7bf174-83d3-440c-93ed-a44b9d28ad31",
      notifyButton: {
        enable: true,
      },
    });
  });
</script>

</body>

</html>
