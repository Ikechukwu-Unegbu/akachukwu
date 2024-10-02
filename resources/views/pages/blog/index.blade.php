@extends('layouts.new-ui')


@section('body')

   
    @include('components.menu-navigation')
   
    <section class="py-16">
        <div class="container mx-auto px-4">
            <!-- Featured Article -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <div class="bg-white rounded-lg shadow-lg">
                    <img src="path/to/featured_image.png" alt="Featured Article Image" class="w-full h-64 object-cover rounded-t-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 uppercase mb-2">A.I. & Recruitment</p>
                        <h3 class="text-2xl font-semibold mb-4">How to Save Money on Airtime and Data Purchases</h3>
                        <p class="text-gray-600 mb-4">
                            The integration of Artificial Intelligence (AI) into the interview process is no longer a futuristic concept—AI is here now, and it’s transforming the way companies find candidates...
                        </p>
                        <p class="text-sm text-gray-400">01/02/2024</p>
                    </div>
                </div>
            </div>

            <!-- Grid of Articles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Article 1 -->
                <div class="bg-white rounded-lg shadow-lg">
                    <img src="path/to/article_image_1.png" alt="Article Image 1" class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 uppercase mb-2">A.I. & Recruitment</p>
                        <h4 class="text-xl font-semibold mb-4">Step-by-Step Guide: How to Send Money to Friends Using Our App</h4>
                        <p class="text-sm text-gray-400">12/03/2024</p>
                    </div>
                </div>

                <!-- Article 2 -->
                <div class="bg-white rounded-lg shadow-lg">
                    <img src="path/to/article_image_2.png" alt="Article Image 2" class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 uppercase mb-2">A.I. & Recruitment</p>
                        <h4 class="text-xl font-semibold mb-4">How Technology is Changing the Way We Handle Finances</h4>
                        <p class="text-sm text-gray-400">10/03/2024</p>
                    </div>
                </div>

                <!-- Article 3 -->
                <div class="bg-white rounded-lg shadow-lg">
                    <img src="path/to/article_image_3.png" alt="Article Image 3" class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 uppercase mb-2">A.I. & Recruitment</p>
                        <h4 class="text-xl font-semibold mb-4">The Rise of Digital Wallets: Why You Should Make the Switch</h4>
                        <p class="text-sm text-gray-400">09/03/2024</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')


@endsection