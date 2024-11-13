{{dd($data)}}
@php 
    $modelName = strtolower(str_replace('Transaction', '', class_basename($data)));
 
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction Success UI</title>
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-lg p-6 shadow-md max-w-sm w-full text-center">
    <!-- Status Icon -->
    <div class="flex items-center border-black justify-center w-[10rem] h-[10rem] rounded-full">
        @php
          $apiResponse = json_decode($data->api_response);
        @endphp
        @if(is_object($apiResponse) && property_exists($apiResponse, 'Status'))

          @if($apiResponse && $apiResponse->Status && $apiResponse->Status == 'successful')
          <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M60 10C32.5 10 10 32.5 10 60C10 87.5 32.5 110 60 110C87.5 110 110 87.5 110 60C110 32.5 87.5 10 60 10ZM60 100C37.95 100 20 82.05 20 60C20 37.95 37.95 20 60 20C82.05 20 100 37.95 100 60C100 82.05 82.05 100 60 100ZM82.95 37.9L50 70.85L37.05 57.95L30 65L50 85L90 45L82.95 37.9Z" fill="#12B76A"/>
          </svg>
          @elseif($apiResponse && ($apiResponse->Status == 'pending' || $apiResponse->Status == 'processing'))
          <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <mask id="mask0_995_10663" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="120" height="120">
          <rect width="120" height="120" fill="#D9D9D9"/>
          </mask>
          <g mask="url(#mask0_995_10663)">
          <path d="M55 70V25H65V70H55ZM55 95V85H65V95H55Z" fill="#FFC700" fill-opacity="0.6"/>
          </g>
          </svg>
          @elseif($apiResponse && $apiResponse->Status && $apiResponse->Status == 'failed')
          <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <mask id="mask0_995_10650" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="120" height="120">
          <rect width="120" height="120" fill="#D9D9D9"/>
          </mask>
          <g mask="url(#mask0_995_10650)">
          <path d="M32 95L25 88L53 60L25 32L32 25L60 53L88 25L95 32L67 60L95 88L88 95L60 67L32 95Z" fill="#FF0000" fill-opacity="0.6"/>
          </g>
          </svg>
          @endif 
        @endif 
        @if(is_object($apiResponse) && property_exists($apiResponse, 'error'))
        <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <mask id="mask0_995_10650" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="120" height="120">
          <rect width="120" height="120" fill="#D9D9D9"/>
          </mask>
          <g mask="url(#mask0_995_10650)">
          <path d="M32 95L25 88L53 60L25 32L32 25L60 53L88 25L95 32L67 60L95 88L88 95L60 67L32 95Z" fill="#FF0000" fill-opacity="0.6"/>
          </g>
          </svg>
        @endif
    </div>

    <!-- Success Message -->
    <h2 class="text-2xl font-semibold text-gray-800 mt-4">Transaction
    @if(is_object($apiResponse) && property_exists($apiResponse, 'Status'))
        @if($apiResponse && $apiResponse->Status && $apiResponse->Status == 'successful')
          Successful!
          @elseif($apiResponse && $apiResponse->Status && ($apiResponse->Status == 'pending' || $apiResponse->Status == 'processing')) 
          Pending!
          @elseif($apiResponse && $apiResponse->Status && $apiResponse->Status == 'failed')
          Failed!
        @endif
      @endif
      @if(is_object($apiResponse) && property_exists($apiResponse, 'error'))
      Failed
      @endif
    </h2>

  

    <p class="text-gray-600 mt-2">
      @if($modelName == 'data' || $modelName == 'airtime')
      {!! $apiResponse->api_response ?? 'No response data available.' !!}
      @else
        @if(is_object($apiResponse) && property_exists($apiResponse, 'Status'))
          @if($apiResponse->Status == 'successful')
            Successfully delivered.
          @endif 
        @endif
      
      @endif 

    </p>

    <!-- Buttons -->
    <div class="flex space-x-4 mt-6">
      <a href="{{route('dashboard')}}" style="background-color: #0018A8;" class="flex-1 bg-[#0018A8] text-white font-semibold py-2 rounded-lg">
        Done
      </a>



<a href="{{ route('user.transaction.' . $modelName . '.receipt', [ $data->transaction_id]) }}" 
   style="border-color: #0018A8; color:#0018A8;" 
   class="flex-1 bg-white text-[#0018A8] border border-[#0018A8] font-semibold py-2 rounded-lg hover:bg-[#0018A8] hover:text-white">
   View Receipt
</a>

    </div>
  </div>

</body>
</html>
