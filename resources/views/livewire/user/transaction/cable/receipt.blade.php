<div>
    <div class="tm_pos_invoice_wrap" id="tm_download_section">
        <div class="tm_pos_invoice_top">
            
            <div class="tm_pos_company_name">{{ config('app.name', 'Laravel') }}</div>
            <div class="tm_pos_company_mobile">{{ $cable->transaction_id }}</div>
        </div>
        <div class="tm_pos_invoice_body">
            <div class="tm_pos_invoice_heading"><span>Cable Receipt</span></div>
            <ul class="tm_list tm_style1">
                <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Card/IUC:</div>
                    <div class="text-right tm_list_desc">{{ $cable->smart_card_number }}</div>
                </li>
                
                <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Plan:</div>
                    <div class="text-right tm_list_desc">{{ $cable->cable_plan_name }}</div>
                </li>

                <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Plan Size:</div>
                    <div class="text-right tm_list_desc">₦ {{ $cable->amount }}</div>
                </li>

                {{-- <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Amount:</div>
                    <div class="text-right tm_list_desc">₦ {{ $cable->amount }}</div>
                </li> --}}

                <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Customer:</div>
                    <div class="text-right tm_list_desc">{{ $cable->customer_name }}</div>
                </li>

                {{-- <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Previous Balance:</div>
                    <div class="text-right tm_list_desc">₦ {{ $cable->balance_before }}</div>
                </li>

                <li class="d-flex justify-content-between">
                    <div class="tm_list_title">New Balance:</div>
                    <div class="text-right tm_list_desc"> {{ $cable->balance_after ? '₦ ' . $cable->balance_after : 'N/A' }}</div>
                </li> --}}

                <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Status:</div>
                    <div class="text-right tm_list_desc">
                        {{ Str::title($cable->vendor_status) }}
                    </div>
                </li>
                
                <li class="d-flex justify-content-between">
                    <div class="tm_list_title">Date:</div>
                    <div class="text-right tm_list_desc"> {{ $cable->created_at->format('M d, Y. h:i a') }}</div>
                </li>
            </ul>

            <div class="tm_pos_sample_text"></div>
            <div class="tm_pos_invoice_footer">Powered by {{ config('app.name', 'Laravel') }}</div>
        </div>
    </div>
    <div class="tm_invoice_btns tm_hide_print">
        <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
            <span class="tm_btn_icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewbox="0 0 512 512">
                    <path
                        d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                        fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                    <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none"
                        stroke="currentColor" stroke-linejoin="round" stroke-width="32"></rect>
                    <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                        stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                    <circle cx="392" cy="184" r="24" fill='currentColor'></circle>
                </svg>
            </span>
            <span class="tm_btn_text">Print</span>
        </a>
        <button id="tm_download_btn" class="tm_invoice_btn tm_color2">
            <span class="tm_btn_icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewbox="0 0 512 512">
                    <path
                        d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03"
                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="32"></path>
                </svg>
            </span>
            <span class="tm_btn_text">Download</span>
        </button>
        <a href="{{ auth()->user()->dashboard() }}" id="tm_download_btn" class="tm_invoice_btn tm_color1">
            <span class="tm_btn_icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                    <path 
                        d="M80 212L256 48l176 164v232a32 32 0 0 1-32 32H112a32 32 0 0 1-32-32V212z" 
                        fill="none" 
                        stroke="#000" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="32">
                    </path>
                    <path 
                        d="M192 448V288h128v160" 
                        fill="none" 
                        stroke="#000" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="32">
                    </path>
                </svg>
            </span>
            <span class="tm_btn_text">Back</span>
        </a>
    </div>
</div>