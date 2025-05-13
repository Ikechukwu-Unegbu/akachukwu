


@extends('layouts.new-guest')
@section('head')
<title>VASTel | savings</title>
@endsection
@section('body')
<section class="lg:px-[2rem] px-4">
    <div class="text-[#0018A8] lg:text-lg  lg:flex gap-4 hidden ">
       <span class="flex justify-center items-center"> <svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6.875 12.0416L1.33333 6.49992L6.875 0.958252" stroke="#0018A8" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
         </span> 
       <span class="flex justify-center items-center">Back</span>
    </div>
{{-- <section class="border-2 border-[#00000038]  mt-16 py-8 mr-30   ">
        <section class=" flex justify-between  ml-8 ]">
        <div class="bg-[#0018A8] rounded-2xl w-1/2 p-6 flex justify-between items-center">
            <div class="items-center">
                <p class="text-white text-sm">Total Savings Balance</p>
                <div class="flex gap-1 ">
                    <span class="font-bold text-white text-lg ">₦0</span>
                    <svg class="mt-1" width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.2602 9.32914C17.2363 9.2743 16.6513 7.97703 15.3449 6.67063C14.1327 5.45984 12.0501 4.01562 8.9999 4.01562C5.94974 4.01562 3.86708 5.45984 2.6549 6.67063C1.34849 7.97703 0.763491 9.27219 0.739585 9.32914C0.715547 9.38314 0.703125 9.44159 0.703125 9.5007C0.703125 9.55981 0.715547 9.61826 0.739585 9.67227C0.763491 9.72641 1.34849 11.0237 2.6549 12.3301C3.86708 13.5409 5.94974 14.9844 8.9999 14.9844C12.0501 14.9844 14.1327 13.5409 15.3449 12.3301C16.6513 11.0237 17.2363 9.72852 17.2602 9.67227C17.2842 9.61826 17.2967 9.55981 17.2967 9.5007C17.2967 9.44159 17.2842 9.38314 17.2602 9.32914ZM8.9999 14.1406C6.79349 14.1406 4.86693 13.3377 3.27294 11.7549C2.60475 11.0909 2.03931 10.3309 1.59529 9.5C2.03918 8.66928 2.60464 7.90949 3.27294 7.24578C4.86693 5.66234 6.79349 4.85938 8.9999 4.85938C11.2063 4.85938 13.1329 5.66234 14.7268 7.24578C15.3952 7.90949 15.9606 8.66928 16.4045 9.5C15.9566 10.3585 13.7108 14.1406 8.9999 14.1406ZM8.9999 6.26562C8.3602 6.26562 7.73487 6.45532 7.20298 6.81072C6.67108 7.16611 6.25653 7.67125 6.01172 8.26226C5.76692 8.85326 5.70287 9.50359 5.82767 10.131C5.95247 10.7584 6.26051 11.3347 6.71285 11.787C7.16518 12.2394 7.7415 12.5474 8.3689 12.6722C8.99631 12.797 9.64663 12.733 10.2376 12.4882C10.8286 12.2434 11.3338 11.8288 11.6892 11.2969C12.0446 10.765 12.2343 10.1397 12.2343 9.5C12.2332 8.64253 11.892 7.82051 11.2857 7.21418C10.6794 6.60786 9.85736 6.26674 8.9999 6.26562ZM8.9999 11.8906C8.52708 11.8906 8.06487 11.7504 7.67174 11.4877C7.2786 11.225 6.97219 10.8517 6.79125 10.4149C6.61031 9.97802 6.56296 9.49735 6.65521 9.03361C6.74745 8.56988 6.97514 8.14391 7.30947 7.80957C7.64381 7.47524 8.06977 7.24755 8.53351 7.15531C8.99725 7.06307 9.47792 7.11041 9.91475 7.29135C10.3516 7.47229 10.7249 7.7787 10.9876 8.17184C11.2503 8.56498 11.3905 9.02718 11.3905 9.5C11.3905 10.134 11.1387 10.7421 10.6903 11.1904C10.242 11.6388 9.63393 11.8906 8.9999 11.8906Z" fill="white"/>
                        </svg>
                        
                </div>
            </div>
             <div>
                <img src="/images/piggy.png" alt="">

                    
             </div>
        </div>
        
    </section>
    <section class="mt-16 ml-10">
        <h3 class="text-[#333333] font-semibold ">Savings Plan</h3>
        <div class="mt-5 rounded-2xl bg-[#E6E6E65C] p-3 w-1/2 border-2 border-[#E6E8F6C9] ">
            <div class="flex justify-between">
                <h4 class="text-[#0018A8] text-lg font-semibold">VasSave</h4>
                <span class="text-[#333333]   font-semibold"><span class="bg-[#E6E8F6D9] px-2 rounded-full">17%p.a</span> <a href="#" class="w-fit font-bold -mt-1">></a> </span> 

            </div>
            
            <p class="text-[#333333] bg-[#E6E8F6D9] pl-3   rounded-xl py-1 mt-3">Save whenever you want, withdraw anytime</p>
            
        </div>

        <div class="mt-2 rounded-2xl bg-[#E6E6E65C] p-3 w-1/2 border-2 border-[#E6E8F6C9] mt- ">
            <div class="flex justify-between">
                <h4 class="text-[#0018A8] text-lg font-semibold">VasTarget</h4>
                <span class="text-[#333333]   font-semibold"><span class="bg-[#E6E8F6D9] px-2 rounded-full">20%p.a</span> <a href="#" class="w-fit font-bold -mt-1">></a> </span> 

            </div>
            
            <p class="text-[#333333] bg-[#E6E8F6D9] pl-3   rounded-xl py-1 mt-3">Save towards a specific goal over time</p>
            
        </div>

        <div class="mt-2 rounded-2xl bg-[#E6E6E65C] p-3 w-1/2 border-2 border-[#E6E8F6C9] mt- ">
            <div class="flex justify-between">
                <h4 class="text-[#0018A8] text-lg font-semibold">VasFixed</h4>
                <span class="text-[#333333]   font-semibold"><span class="bg-[#E6E8F6D9] px-2 rounded-full">30%p.a</span> <a href="#" class="w-fit font-bold -mt-1">></a> </span> 

            </div>
            
            <p class="text-[#333333] bg-[#E6E8F6D9] pl-3   rounded-xl py-1 mt-3">Lock funds for a set periods and earn higher retrurn</p>
            
        </div>

    </section>


</section> --}}


<section class="lg:border border-[#00000038] lg:my-8 lg:py-5  mt-8 py-4 lg:w-6/12 ">
    <section class="flex flex-col lg:flex-row justify-between  mr-4 lg:ml-8 lg:mr-8">
        <div class="bg-[#0018A8] rounded-2xl w-full p-6 flex justify-between items-center mb-6 lg:mb-0">
            <div>
                <p class="text-white text-sm  ">Total Savings Balance</p>
                <div class="flex gap-1 items-center">
                    <span class="font-bold text-white text-lg" id="amount">₦0</span>
                    <button id="toggleVisibility" class="focus:outline-none">
                        <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.2602 9.32914C17.2363 9.2743 16.6513 7.97703 15.3449 6.67063C14.1327 5.45984 12.0501 4.01562 8.9999 4.01562C5.94974 4.01562 3.86708 5.45984 2.6549 6.67063C1.34849 7.97703 0.763491 9.27219 0.739585 9.32914C0.715547 9.38314 0.703125 9.44159 0.703125 9.5007C0.703125 9.55981 0.715547 9.61826 0.739585 9.67227C0.763491 9.72641 1.34849 11.0237 2.6549 12.3301C3.86708 13.5409 5.94974 14.9844 8.9999 14.9844C12.0501 14.9844 14.1327 13.5409 15.3449 12.3301C16.6513 11.0237 17.2363 9.72852 17.2602 9.67227C17.2842 9.61826 17.2967 9.55981 17.2967 9.5007C17.2967 9.44159 17.2842 9.38314 17.2602 9.32914ZM8.9999 14.1406C6.79349 14.1406 4.86693 13.3377 3.27294 11.7549C2.60475 11.0909 2.03931 10.3309 1.59529 9.5C2.03918 8.66928 2.60464 7.90949 3.27294 7.24578C4.86693 5.66234 6.79349 4.85938 8.9999 4.85938C11.2063 4.85938 13.1329 5.66234 14.7268 7.24578C15.3952 7.90949 15.9606 8.66928 16.4045 9.5C15.9566 10.3585 13.7108 14.1406 8.9999 14.1406ZM8.9999 6.26562C8.3602 6.26562 7.73487 6.45532 7.20298 6.81072C6.67108 7.16611 6.25653 7.67125 6.01172 8.26226C5.76692 8.85326 5.70287 9.50359 5.82767 10.131C5.95247 10.7584 6.26051 11.3347 6.71285 11.787C7.16518 12.2394 7.7415 12.5474 8.3689 12.6722C8.99631 12.797 9.64663 12.733 10.2376 12.4882C10.8286 12.2434 11.3338 11.8288 11.6892 11.2969C12.0446 10.765 12.2343 10.1397 12.2343 9.5C12.2332 8.64253 11.892 7.82051 11.2857 7.21418C10.6794 6.60786 9.85736 6.26674 8.9999 6.26562ZM8.9999 11.8906C8.52708 11.8906 8.06487 11.7504 7.67174 11.4877C7.2786 11.225 6.97219 10.8517 6.79125 10.4149C6.61031 9.97802 6.56296 9.49735 6.65521 9.03361C6.74745 8.56988 6.97514 8.14391 7.30947 7.80957C7.64381 7.47524 8.06977 7.24755 8.53351 7.15531C8.99725 7.06307 9.47792 7.11041 9.91475 7.29135C10.3516 7.47229 10.7249 7.7787 10.9876 8.17184C11.2503 8.56498 11.3905 9.02718 11.3905 9.5C11.3905 10.134 11.1387 10.7421 10.6903 11.1904C10.242 11.6388 9.63393 11.8906 8.9999 11.8906Z" fill="white"/>
                            </svg>
                    </button>
                   
                        
                </div>
                <script>
                    const toggleBtn = document.getElementById('toggleVisibility');
                    const amount = document.getElementById('amount');
                
                    let visible = true;
                
                    toggleBtn.addEventListener('click', () => {
                        visible = !visible;
                        amount.textContent = visible ? "₦0" : "****";
                    });
                </script>
            </div>
            <div>
                <img src="/images/piggy.png" alt="">
            </div>
        </div>
    </section>

    <section class="mt-8 lg:mt-10 ml-4 lg:ml-10 mr-4">
        <h3 class="text-[#333333] font-semibold mb-4">Savings Plan</h3>

        <!-- Plan Box -->
        <div class="rounded-2xl bg-[#E6E6E65C] p-3 w-full  border-2 border-[#E6E8F6C9] mb-4">
            <div class="flex justify-between">
                <h4 class="text-[#0018A8] text-lg font-semibold">VasSave</h4>
                <span class="text-[#333333] flex gap-2 font-semibold">
                    <span class="bg-[#E6E8F6D9] px-2 rounded-full">17%p.a</span>
                    <a href="{{ route('vasSave') }}" class="items-center flex justify-center">
                        <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 0.833415L5.66667 5.50008L1 10.1667" stroke="black" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                    </a>
                </span>
            </div>
            <p class="text-[#333333] bg-[#E6E8F6D9] pl-2 rounded-xl py-1 mt-3 text-sm  ">Save whenever you want, withdraw anytime</p>
        </div>

        <div class="rounded-2xl bg-[#E6E6E65C] p-3 w-full  border-2 border-[#E6E8F6C9] mb-4">
            <div class="flex justify-between">
                <h4 class="text-[#0018A8] text-lg font-semibold">VasTarget</h4>
                <span class="text-[#333333] flex gap-2 font-semibold">
                    <span class="bg-[#E6E8F6D9] px-2 rounded-full">20%p.a</span>
                    <a href="{{ route('vasTarget') }}" class="items-center flex justify-center">
                        <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 0.833415L5.66667 5.50008L1 10.1667" stroke="black" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                    </a>
                </span>
            </div>
            <p class="text-[#333333] bg-[#E6E8F6D9] pl-2 rounded-xl py-1 mt-3 text-sm">Save towards a specific goal over time</p>
        </div>

        <div class="rounded-2xl bg-[#E6E6E65C] p-3 w-full  border-2 border-[#E6E8F6C9]">
            <div class="flex justify-between">
                <h4 class="text-[#0018A8] text-lg font-semibold">VasFixed</h4>
                <span class="text-[#333333] flex gap-2 font-semibold">
                    <span class="bg-[#E6E8F6D9] px-2 rounded-full">30%p.a</span>
                    <a href="#" class="items-center flex justify-center">
                        <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 0.833415L5.66667 5.50008L1 10.1667" stroke="black" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                    </a>
                </span>
            </div>
            <p class="text-[#333333] bg-[#E6E8F6D9] pl-2 rounded-xl py-1 mt-3 text-sm">Lock funds for a set period and earn higher return</p>
        </div>
    </section>
</section>

</section>


    
@endsection
