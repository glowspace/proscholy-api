@php
    $is_active = \Route::current()->getName() == $route;
@endphp

<div class="btn-group m-0 d-lg-flex" role="group" aria-label="Basic example">
    <a
        class="btn btn-secondary {{ $is_active ? 'active' : '' }}"
        href="{{route($route).(($plus ?? false) ? '#' : '')}}"
        onclick="{{($plus ?? false) && $is_active ? "if(document.getElementById('search')){document.getElementById('search').focus();}" : ''}}"
        ><i class="fas fa-{{$icon}}"></i><span>{{$text}}</span>
        @if(isset($badge) && $badge > 0)
        <span class="badge badge-pill">{{$badge}}</span>
        @endif
    </a>
    @if(($plus ?? false) || ($plus_route ?? false))
    <a
        class="btn btn-secondary d-lg-inline-block pr-1 {{ $is_active ? 'active' : '' }}"
        style="width:0;display:none"
        href="{{($plus_route ?? false) ? route($plus_route) : route($route).'#n'}}"
        onclick="{{($plus ?? false) && $is_active ? "if(document.getElementById('create-model-text-field')){document.getElementById('create-model-text-field').focus();}" : ''}}"
        ><i class="fas fa-plus"></i>
    </a>
    @endif
</div>
