@php
    $is_active = \Route::current()->getName() == $route;
@endphp

<a class="btn btn-secondary {{ $is_active ? 'active' : '' }}" href="{{route($route)}}">
    <i class="fas fa-{{$icon}}"></i><span>{{$text}}</span>
    @if(isset($badge) && $badge > 0)
        <span class="badge badge-pill">{{$badge}}</span>
    @endif
</a>
