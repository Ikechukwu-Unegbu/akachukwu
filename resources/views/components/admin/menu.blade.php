<li class="nav-item">
    <a class="nav-link " href="{{ $link ?? '#' }}" style="background: {{ request()->url() === $link ? '#f6f9ff;' : 'none' }} !important">
      <i class="bi {{ $icon ?? '' }}"></i>
      <span>{{ $title ?? '' }}</span>
    </a>
</li>