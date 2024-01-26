<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#{{ Str::slug($title) }}-nav" data-bs-toggle="collapse" href="#">
      <i class="bx {{ $icon ?? 'bi-menu-button-wide' }}">
        </i><span>{{ $title ?? '' }}</span>
        <i class="bi bi-chevron-down ms-auto"></i>
    </a>

    <ul id="{{ Str::slug($title) }}-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        {{ $slot ?? '' }}
    </ul>
</li>