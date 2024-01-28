<li class="breadcrumb-item {{ isset($status) && !is_null($status) ? 'active' : '' }}">
    @isset ($link)
        <a href="{{ $link ?? 'javascript:void(0)' }}">
    @endisset 
        {{ $subtitle ?? '' }} 
    @isset ($link)
    </a> @endisset
</li>