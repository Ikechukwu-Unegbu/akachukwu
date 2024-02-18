<div class="card info-card sales-card">
    <div class="card-body">
        <h5 class="card-title">{{ $title }} @isset ($day) <span>| Today</span> @endisset</h5>
        <div class="d-flex align-items-center">
            <div
                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi {{ $icon ?? '' }}"></i>
            </div>
            <div class="ps-3">
                <h6>@isset($currency) {{ $currency }} @endisset {{ number_format($data), 2 }}</h6>
            </div>
        </div>
    </div>
</div>