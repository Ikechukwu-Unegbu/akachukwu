@extends('layouts.new-guest')
@section('head')
    <title>Vastel | Leaderboard</title>
@endsection
@section('body')
    <section class="px-8">
        <div class="flex gap-2 my-4 ">
            <button>
                <svg width="19" height="19" class="w-fit" viewBox="0 0 19 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.875 15.0416L6.33333 9.49992L11.875 3.95825" stroke="#0018A8" stroke-width="1.58333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </button>


            <p class="text-[#0018A8] flex justify-center items-center w-fit -mt-1 text-[16px]">Back</p>
        </div>



        <section class="  lg:w-[500px] w-full">


            <div class="bg-blue-50 pt-4 flex flex-col rounded-2xl">
                <div class="flex justify-between px-2">
                    <div>
                        <p class="text-black">
                            Invite friends and earn up to<br> #500,000 for each referral
                        </p>
                    </div>
                    <div class="rounded-full w-16 h-16 flex justify-center items-center bg-gray-200">
                        <svg width="27" height="29" viewBox="0 0 37 39" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M37 9.28571H28.12C29.045 8.35714 29.6 7.05714 29.6 5.57143C29.6 2.41429 27.195 0 24.05 0C20.905 0 18.5 2.41429 18.5 5.57143C18.5 2.41429 16.095 0 12.95 0C9.805 0 7.4 2.41429 7.4 5.57143C7.4 7.05714 7.955 8.35714 8.88 9.28571H0V20.4286H1.85V35.2857C1.85 37.3286 3.515 39 5.55 39H31.45C33.485 39 35.15 37.3286 35.15 35.2857V20.4286H37V9.28571ZM33.3 16.7143H20.35V13H33.3V16.7143ZM24.05 3.71429C25.16 3.71429 25.9 4.45714 25.9 5.57143C25.9 6.68571 25.16 7.42857 24.05 7.42857C22.94 7.42857 22.2 6.68571 22.2 5.57143C22.2 4.45714 22.94 3.71429 24.05 3.71429ZM12.95 3.71429C14.06 3.71429 14.8 4.45714 14.8 5.57143C14.8 6.68571 14.06 7.42857 12.95 7.42857C11.84 7.42857 11.1 6.68571 11.1 5.57143C11.1 4.45714 11.84 3.71429 12.95 3.71429ZM3.7 13H16.65V16.7143H3.7V13ZM5.55 20.4286H16.65V35.2857H5.55V20.4286ZM31.45 35.2857H20.35V20.4286H31.45V35.2857Z"
                                fill="#3346B9" />
                        </svg>
                    </div>
                </div>
                <div
                    class="bg-blue-700 mt-6 text-white justify-center flex items-center text-center py-2 rounded-t-none rounded-b-2xl">
                    <span>Ends on 10 may 2025</span>
                </div>
            </div>


            <div class="mt-2 text-[#333333] ">
                <h3 class="font-semibold text-black text-lg">Instructions</h3>

                <ul class="ml-2 text-sm list-none">
                    <li class="mt-2 relative pl-5">
                        <span class="absolute left-0">•</span>
                        Invite a friend who is not an existing Vastel customer
                    </li>
                    <li class="mt-2 relative pl-5">
                        <span class="absolute left-0">•</span>
                        Friend accepts the invitation by using your referral code during onboarding
                    </li>
                    <li class="mt-2 relative pl-5">
                        <span class="absolute left-0">•</span>
                        You immediately get cashback of <span class="font-bold">#250</span>
                    </li>
                    <li class="mt-2 relative pl-5 font-bold">
                        <span class="absolute left-0">•</span>
                        The more money your friend deposits, the more cash you will earn
                    </li>
                </ul>

            </div>
            <hr class="w-full mt-4 p-2">

            <div class="mt-10">
                <div class="flex justify-between">
                    <div class="flex gap-3">
                        <div
                            class="rounded-full w-10 h-10 flex justify-center items-center border border-[#3346B9] bg-[#E6E8F6]">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.05039 4.96198C5.32255 5.05778 5.61221 5.09356 5.89949 5.06686C6.18678 5.04017 6.46489 4.95163 6.71473 4.80733C6.96458 4.66303 7.18024 4.46637 7.34693 4.23087C7.51361 3.99537 7.62737 3.72659 7.68039 3.44298L8.00039 1.72298C9.3162 1.42323 10.6826 1.42323 11.9984 1.72298L12.3204 3.44298C12.3734 3.72659 12.4872 3.99537 12.6539 4.23087C12.8205 4.46637 13.0362 4.66303 13.286 4.80733C13.5359 4.95163 13.814 5.04017 14.1013 5.06686C14.3886 5.09356 14.6782 5.05778 14.9504 4.96198L16.5994 4.38198C17.5173 5.37075 18.2012 6.55324 18.6004 7.84198L17.2704 8.98198C17.0513 9.16974 16.8755 9.40266 16.755 9.66477C16.6344 9.92688 16.572 10.212 16.572 10.5005C16.572 10.789 16.6344 11.0741 16.755 11.3362C16.8755 11.5983 17.0513 11.8312 17.2704 12.019L18.6004 13.158C18.2012 14.4467 17.5173 15.6292 16.5994 16.618L14.9494 16.038C14.6772 15.9422 14.3876 15.9064 14.1003 15.9331C13.813 15.9598 13.5349 16.0483 13.285 16.1926C13.0352 16.3369 12.8195 16.5336 12.6529 16.7691C12.4862 17.0046 12.3724 17.2734 12.3194 17.557L12.0004 19.277C10.6846 19.5767 9.3182 19.5767 8.00239 19.277L7.68039 17.557C7.62737 17.2734 7.51361 17.0046 7.34693 16.7691C7.18024 16.5336 6.96458 16.3369 6.71473 16.1926C6.46489 16.0483 6.18678 15.9598 5.89949 15.9331C5.61221 15.9064 5.32255 15.9422 5.05039 16.038L3.40139 16.618C2.48348 15.6292 1.79962 14.4467 1.40039 13.158L2.73039 12.018C2.94927 11.8302 3.12495 11.5973 3.2454 11.3353C3.36585 11.0733 3.42821 10.7884 3.42821 10.5C3.42821 10.2116 3.36585 9.92664 3.2454 9.66462C3.12495 9.40261 2.94927 9.16974 2.73039 8.98198L1.40039 7.84198C1.79933 6.55333 2.48284 5.37085 3.40039 4.38198L5.05039 4.96198ZM10.0004 7.49998C10.796 7.49998 11.5591 7.81605 12.1217 8.37866C12.6843 8.94127 13.0004 9.70433 13.0004 10.5C13.0004 11.2956 12.6843 12.0587 12.1217 12.6213C11.5591 13.1839 10.796 13.5 10.0004 13.5C9.20474 13.5 8.44168 13.1839 7.87907 12.6213C7.31646 12.0587 7.00039 11.2956 7.00039 10.5C7.00039 9.70433 7.31646 8.94127 7.87907 8.37866C8.44168 7.81605 9.20474 7.49998 10.0004 7.49998Z"
                                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <p class="font-bold flex justify-center items-center text-center">View reward structure</p>

                    </div>
                    <button>
                        <svg width="19" class="flex justify-center text-center items-center h-fit w-fit mt-3"
                            height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.125 3.95829L12.6667 9.49996L7.125 15.0416" stroke="black" stroke-width="1.58333"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>


                </div>



                <div class="flex justify-between mt-6">
                    <div class="flex gap-3">
                        <div class="rounded-full w-10 h-10 flex justify-center items-center ">

                            <svg width="24" height="50" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.5 1.375C4.5 1.20924 4.56585 1.05027 4.68306 0.933058C4.80027 0.815848 4.95924 0.75 5.125 0.75H18.875C19.0408 0.75 19.1997 0.815848 19.3169 0.933058C19.4342 1.05027 19.5 1.20924 19.5 1.375V2H22.625C22.7908 2 22.9497 2.06585 23.0669 2.18306C23.1842 2.30027 23.25 2.45924 23.25 2.625V6.375C23.25 7.2038 22.9208 7.99866 22.3347 8.58471C21.7487 9.17076 20.9538 9.5 20.125 9.5H19.0731C18.11 12.225 15.6169 14.2275 12.625 14.4744V18.25H17C17.1658 18.25 17.3247 18.3158 17.4419 18.4331C17.5592 18.5503 17.625 18.7092 17.625 18.875V22.625C17.625 22.7908 17.5592 22.9497 17.4419 23.0669C17.3247 23.1842 17.1658 23.25 17 23.25H7C6.83424 23.25 6.67527 23.1842 6.55806 23.0669C6.44085 22.9497 6.375 22.7908 6.375 22.625V18.875C6.375 18.7092 6.44085 18.5503 6.55806 18.4331C6.67527 18.3158 6.83424 18.25 7 18.25H11.375V14.4744C8.38375 14.2275 5.89 12.225 4.92687 9.5H3.875C3.0462 9.5 2.25134 9.17076 1.66529 8.58471C1.07924 7.99866 0.75 7.2038 0.75 6.375V2.625C0.75 2.45924 0.815848 2.30027 0.933058 2.18306C1.05027 2.06585 1.20924 2 1.375 2H4.5V1.375ZM18.25 7V2H5.75V7C5.75 10.4519 8.54813 13.25 12 13.25C15.4519 13.25 18.25 10.4519 18.25 7ZM19.5 3.25V8.25H20.125C20.6223 8.25 21.0992 8.05246 21.4508 7.70083C21.8025 7.34919 22 6.87228 22 6.375V3.25H19.5ZM2 3.25H4.5V8.25H3.875C3.37772 8.25 2.90081 8.05246 2.54917 7.70083C2.19754 7.34919 2 6.87228 2 6.375V3.25ZM7.625 19.5V22H16.375V19.5H7.625Z"
                                    fill="black" />
                            </svg>


                        </div>
                        <div class="flex flex-col">
                            <p class="font-bold flex ">Leaderboard</p>
                            <p class="text-[#545454]">See top referrals earners</p>
                        </div>


                    </div>
                    <button>
                        <svg width="19" class="flex justify-center text-center items-center h-fit w-fit mt-3"
                            height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.125 3.95829L12.6667 9.49996L7.125 15.0416" stroke="black" stroke-width="1.58333"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>


                </div>

            </div>

            <div class="p-3 flex justify-between bg-[#E6E6E663] mt-6 rounded-2xl">
                <div class="flex flex-col">
                    <p class="text-[#545454]">Referral Code</p>
                    <p class="font-bold text-[#333333]">AB123</p>

                </div>
                <button class="flex justify-center items-center">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.5 6.25V17.5H6.25V6.25H17.5ZM17.5 5H6.25C5.91848 5 5.60054 5.1317 5.36612 5.36612C5.1317 5.60054 5 5.91848 5 6.25V17.5C5 17.8315 5.1317 18.1495 5.36612 18.3839C5.60054 18.6183 5.91848 18.75 6.25 18.75H17.5C17.8315 18.75 18.1495 18.6183 18.3839 18.3839C18.6183 18.1495 18.75 17.8315 18.75 17.5V6.25C18.75 5.91848 18.6183 5.60054 18.3839 5.36612C18.1495 5.1317 17.8315 5 17.5 5Z"
                            fill="#030862" />
                        <path
                            d="M2.5 11.25H1.25V2.5C1.25 2.16848 1.3817 1.85054 1.61612 1.61612C1.85054 1.3817 2.16848 1.25 2.5 1.25H11.25V2.5H2.5V11.25Z"
                            fill="#030862" />
                    </svg>


                </button>

            </div>

        </section>


    </section>
@endsection
