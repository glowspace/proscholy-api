@php
    $is_active = \Route::current()->getName() == $route;
@endphp

<div class="btn-group m-0 d-lg-flex" role="group" aria-label="Basic example">
    <a
        class="btn btn-secondary {{ $is_active ? 'active' : '' }}"
        href="{{ route($route) }}"
        onclick="{{ $is_active ? "if(document.getElementById('search')){event.preventDefault();document.getElementById('search').focus();}" : '' }}"
        ><i class="fas fa-{{ $icon }}"></i><span>{{ $text }}</span>
        @if(isset($badge) && $badge > 0)
        <span class="badge badge-pill">{{ $badge }}</span>
        @endif
    </a>
</div>
