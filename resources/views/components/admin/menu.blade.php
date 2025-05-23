<li class="nav-item">
    <a class="nav-link " href="{{ $link ?? '#' }}" style="background: {{ request()->url() === $link ? '#f6f9ff;' : 'none' }} !important; color: {{ request()->url() === $link ? '#4154f1;' : '#012970' }} !important">
      <i class="bi {{ $icon ?? '' }}" style="color: {{ request()->url() === $link ? '#4154f1;' : '#012970' }} !important"></i>
      <span>{{ $title ?? '' }}</span>
    </a>
</li>
