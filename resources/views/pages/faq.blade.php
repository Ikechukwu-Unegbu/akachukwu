@extends('layouts.new-ui')


@section('body')

   
    @include('components.menu-navigation')
   
    <div class="mt-[3rem] mb-[3rem] bg-white px-[1rem] md:px-[15%] rounded-lg ">
        <h2 class="text-2xl font-semibold text-center mb-4">Frequently Asked Questions</h2>

        <div id="accordion" data-accordion="collapse">
            <!-- Getting Started Section -->
            @foreach ($categories as $category)
            <h3 class="text-2xl font-semibold mb-2 mt-[2rem]">{{$category->name}}</h3>

                @foreach($category->posts as $faq)
                <div class="border-b border-gray-200">
                    <button type="button" class="flex items-center justify-between w-full p-4 text-left text-gray-500 font-medium hover:bg-gray-100" data-accordion-target="#item1-{{$faq->id}}" aria-expanded="true" aria-controls="item1-{{$faq->id}}">
                        {!!$faq->excerpt!!}
                        <svg data-accordion-icon class="w-6 h-6 ml-2">
                            <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="item1-{{$faq->id}}" class="hidden p-4 text-gray-600">
                        <p>{!!$faq->content!!}</p>
                    </div>
                </div>
                @endforeach 

               

            @endforeach
           
            <!-- Wallet & Payments Section -->
           {{-- <h3 class="text-lg font-semibold mt-6 mb-2">Wallet & Payments</h3>

            <div class="border-b border-gray-200">
                <button type="button" class="flex items-center justify-between w-full p-4 text-left text-gray-500 font-medium hover:bg-gray-100" data-accordion-target="#item4" aria-expanded="false" aria-controls="item4">
                    How can I fund my wallet?
                    <svg data-accordion-icon class="w-6 h-6 ml-2">
                        <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="item4" class="hidden p-4 text-gray-600">
                    <p>Navigate to the wallet section and choose a payment method to fund your wallet.</p>
                </div>
            </div>

            <div class="border-b border-gray-200">
                <button type="button" class="flex items-center justify-between w-full p-4 text-left text-gray-500 font-medium hover:bg-gray-100" data-accordion-target="#item5" aria-expanded="false" aria-controls="item5">
                    How do I view my transaction history?
                    <svg data-accordion-icon class="w-6 h-6 ml-2">
                        <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="item5" class="hidden p-4 text-gray-600">
                    <p>Go to the "Transaction History" section in your wallet to view all transactions.</p>
                </div>
            </div>--}}
        </div>
    </div>




    @include('components.footer')

@endsection